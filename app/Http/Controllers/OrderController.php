<?php

namespace App\Http\Controllers;
use App\Models\Carts;
use App\Models\Order;
use Illuminate\Http\Request;
class OrderController extends Controller
{
 

public function placeOrder(Request $request)
{
    $userId= $request->get('user_id');
    
    // $request->validate([
    //     'pharmacy_id' => 'required',
    //     'items_price' => 'required|numeric',
    //     'delivery_address' => 'required|string',
    //     'delivery_options' => 'required|string',
    //     'add_patient' => 'required|string',
    //     'payment_option' => 'required|string',
    //     'distance' => 'required|numeric',
    // ]);
    
    try {
        
        $cartItems = Carts::where('customer_id', $userId)->get();
        // echo $userId;die;

        if ($cartItems->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Cart is empty.'
            ], 400);
        }

        $productDetails = $cartItems->map(function ($item) {
            return [
                'product_id' => $item->product_id,
                'name' => $item->product_name,
                'quantity' => $item->quantity,
                'price' => $item->price,
            ];
        });

        $distance = $request->distance;
        if ($distance <= 5) {
            $deliveryCharges = 30;
        } elseif ($distance <= 10) {
            $deliveryCharges = 50;
        } else {
            $deliveryCharges = 70;
        }

        $platformFees = 4;
        $totalPrice = $request->items_price + $platformFees + $deliveryCharges;

        $lastOrder = Order::orderBy('id', 'desc')->first();

if ($lastOrder) {
    $lastNumber = (int) substr($lastOrder->order_id, 1);
    $newNumber = $lastNumber + 1;
} else {
    $newNumber = 1;
}

$orderId = 'P' . str_pad($newNumber, 6, '0', STR_PAD_LEFT);

        $order = Order::create([
            'order_id' => $orderId,
            'user_id' =>$userId,
            'pharmacy_id' => $request->pharmacy_id,
            'product_details' => $productDetails->toJson(),  // cart se directly
            'items_price' => $request->items_price,
            'platform_fees' => $platformFees,
            'delivery_charges' => $deliveryCharges,
            'total_price' => $totalPrice,
            'delivery_charges'=>$request->delivery_charges,
            'delivery_address' => $request->delivery_address,
            'delivery_options' => $request->delivery_options,
            'add_patient' => $request->add_patient,
            'payment_option' => $request->payment_option,
            'status' => 'pending',
        ]);

        // Cart clear karo
    //    Carts::where('customer_id', $userId)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Order placed successfully',
            'order_id' => $order->order_id,
            'total_price' => $totalPrice,
            'delivery_charges' => $deliveryCharges
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Something went wrong',
            'error' => $e->getMessage()
        ], 500);
    }
}




   
}
