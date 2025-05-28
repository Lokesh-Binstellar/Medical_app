<?php

namespace App\Http\Controllers;

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
                ->select('orders.order_id', 'orders.status', 'orders.product_details', 'orders.total_price', 
                'orders.items_price', 'orders.delivery_charges', 'orders.platform_fees', 'orders.delivery_address', 'orders.created_at', 'pharmacies.pharmacy_name', 
                'pharmacies.image as pharmacy_image')
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
}
