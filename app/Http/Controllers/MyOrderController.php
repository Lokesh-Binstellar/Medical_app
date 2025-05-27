<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class MyOrderController extends Controller
{
    public function getUserPharmacyOrders(Request $request)
    {
        $customerId = $request->get('user_id');

        try {
            $orders = Order::where('user_id', $customerId)
                ->with('pharmacy:id,pharmacy_name')
                ->select('order_id', 'pharmacy_id', 'status', 'product_details', 'created_at')
                ->get()
                ->map(function ($order) {
                    // Try to decode product_details JSON
                    try {
                        $products = json_decode($order->product_details, true);

                        // If JSON decoding fails (null and not an empty array)
                        if ($products === null && json_last_error() !== JSON_ERROR_NONE) {
                            throw new \Exception('Invalid product_details JSON');
                        }
                    } catch (\Exception $e) {
                        $products = [];
                    }

                    return [
                        'order_id' => $order->order_id,
                        'pharmacy_name' => $order->pharmacy->pharmacy_name ?? 'N/A',
                        'status' => $order->status,
                        'ordered_at' => $order->created_at->format('Y-m-d H:i:s'),
                        'product_details' => $products,
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
                    'message' => 'Something went wrong',
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
    }
}
