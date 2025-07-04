<?php

namespace App\Http\Controllers;

use App\Events\MyEvent;
use App\Models\Medicine;
use App\Models\Order;
use App\Models\Otcmedicine;
use App\Models\Pharmacies;
use Illuminate\Http\Request;

class MyOrderController extends Controller
{
    public function getUserPharmacyOrders(Request $request)
    {
        $userId = $request->get('user_id');

        //Base URLs and default image
        $medicineBaseUrl = url('medicines');
        $pharmacyImageBaseUrl = url('assets/image');
        $defaultImage = "{$medicineBaseUrl}/placeholder.png";

        try {
            // Get orders joined with pharmacy info
            $orders = Order::join('pharmacies', 'orders.pharmacy_id', '=', 'pharmacies.user_id')
                ->where('orders.user_id', $userId)
                ->select(
                    'orders.order_id',
                    'orders.status',
                    'orders.product_details',
                    'orders.total_price',
                    'orders.pharmacy_id',
                    'orders.items_price',
                    'orders.delivery_charges',
                    'orders.platform_fees',
                    'orders.delivery_address',
                    'orders.created_at',
                    'pharmacies.pharmacy_name',
                    'pharmacies.image as pharmacy_image'
                )
                ->get()
                ->map(function ($order) use ($medicineBaseUrl, $pharmacyImageBaseUrl, $defaultImage) {

                    $products = json_decode($order->product_details, true);
                    if ($products === null && json_last_error() !== JSON_ERROR_NONE) {
                        $products = [];
                    }
                    $products = collect($products)
                        ->map(function ($product) use ($medicineBaseUrl, $defaultImage) {
                            $productId = $product['medicine_id'] ?? null;
                            $images = [];

                            if ($productId) {
                                $medicine = Medicine::where('product_id', $productId)->first();
                                if ($medicine && $medicine->image) {
                                    $images = array_map(fn($img) => "{$medicineBaseUrl}/" . trim($img), explode(',', $medicine->image));
                                } else {
                                    $otc = Otcmedicine::where('otc_id', $productId)->first();
                                    if ($otc && $otc->image) {
                                        $images = array_map(fn($img) => "{$medicineBaseUrl}/" . trim($img), explode(',', $otc->image));
                                    } else {
                                        $images = [$defaultImage];
                                    }
                                }
                            } else {
                                $images = [$defaultImage];
                            }

                            $product['images'] = $images;
                            return $product;
                        })
                        ->toArray();

                    $statusText = match ((int) $order->status) {
                        0 => 'Pending',
                        1 => 'Completed',
                        2 => 'Cancelled',
                        default => 'Unknown',
                    };

                    return [
                        'order_id' => $order->order_id,
                        'id' => $order->pharmacy_id ?? 'N/A',
                        'pharmacy_name' => $order->pharmacy_name ?? 'N/A',
                        'pharmacy_image' => $order->pharmacy_image ? "{$pharmacyImageBaseUrl}/{$order->pharmacy_image}" : $defaultImage,
                        'product_details' => $products,
                        'sub_total' => $order->items_price,
                        'platform_fees' => $order->platform_fees,
                        'delivery_Charges' => $order->delivery_charges,
                        'total_price' => $order->total_price,
                        'delivery_address' => $order->delivery_address,
                        'status' => $statusText,
                        'date' => \Carbon\Carbon::parse($order->created_at)->format('d F Y'),
                    ];
                });

            return response()->json([
                'status' => true,
                'data' => $orders,
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Something went wrong.',
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function cancelOrder(Request $request, $order_id)
    {
        try {
            // Find the order by order_id (custom primary key)
            $order = Order::where('order_id', $order_id)->firstOrFail();

            // Determine who is cancelling the order
            $cancelBy = 'customer'; // default

            // Update the status to 2 (cancelled)
            $order->update([
                'status' => 2,
                'cancelled_at' => now(), // Optional: add cancellation timestamp
                'cancel_by' => $cancelBy
            ]);


            // Optional: Trigger any cancellation events/notifications
            event(new MyEvent('admin', null, 'Order has been cancelled by customer. Please do the refund.'));

            // Default success message
            $message = 'Order has been cancelled successfully.';

            // Add refund notice for online payments
            if ($order->payment_option === 'pay_online') {
                $message .= ' Since the payment was made online, the amount will be refunded to your account within 3-5 working days.';
            }

            return response()->json([
                'status' => true,
                'message' => $message
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to cancel order: ' . $e->getMessage()
            ], status: 200);
        }
    }
  public function returnOrder(Request $request, $order_id)
{
    try {
        $order = Order::where('order_id', $order_id)->firstOrFail();

        // ✅ Allow only status 1 or 3
        if (!in_array($order->status, [1, 3])) {
            return response()->json([
                'status' => false,
                'message' => 'Only completed or partially returned orders can be returned.'
            ]);

        //     // Optional: Trigger any return processing events
        //     // event(new OrderReturned($order));

        //     return response()->json([
        //         'success' => true,
        //         'message' => 'Order has been successfully marked as returned.'
        //     ]);

        // } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Order not found'
        //     ], 200);
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Failed to process return: ' . $e->getMessage()
        //     ], 200);
        }

        // ✅ Return window: 7 days
        $sevenDaysAgo = now()->subDays(7);
        if ($order->created_at->lt($sevenDaysAgo)) {
            return response()->json([
                'status' => false,
                'message' => 'Return window expired.'
            ]);
        }

        // ✅ Must have return_items
        $returnItems = $request->input('return_items', []);
        if (empty($returnItems)) {
            return response()->json([
                'status' => false,
                'message' => 'Please select at least one item to return.'
            ]);
        }

        // ✅ Collect valid medicine IDs
        $productDetails = json_decode($order->product_details, true);
        $validMedicineIds = collect($productDetails)->pluck('medicine_id')->toArray();

        // ✅ Check if ALL provided IDs are valid
        $invalidIds = array_diff($returnItems, $validMedicineIds);
        if (!empty($invalidIds)) {
            return response()->json([
                'status' => false,
                'message' => 'Some selected items are invalid. Please check your selection.'
            ]);
        }

        // ✅ If ALL are valid, build returned items
        $returnedMedicines = [];
        foreach ($productDetails as $item) {
            if (in_array($item['medicine_id'], $returnItems)) {
                $returnedMedicines[] = $item;
            }
        }

        // ✅ Store valid items
        $order->returned_items = json_encode($returnedMedicines);
        $order->status = 3; // optional: mark returned
        $order->save();

        return response()->json([
            'status' => true,
            'message' => 'Returned items saved successfully.'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Failed: ' . $e->getMessage()
        ]);
    }
}

}
