<?php

namespace App\Http\Controllers;
use App\Models\Additionalcharges;
use App\Models\Carts;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\Pharmacies;
use App\Models\Phrmacymedicine;
use Illuminate\Http\Request;
class OrderController extends Controller
{


    public function placeOrder(Request $request)
    {
        $userId = $request->get('user_id');

        try {

            $cartItems = Carts::where('customer_id', $userId)->first();


            if (!$cartItems) {
                return response()->json([
                    'status' => false,
                    'message' => 'Cart is empty.'
                ], 400);
            }
            $products = json_decode($cartItems->products_details, true);

            $productData = Phrmacymedicine::where('customer_id', $userId)->first();


            $customerAddDetails = CustomerAddress::where('customer_id', $userId)
                ->where('address_type', $request->delivery_address)
                ->first();

            // Save users current address & pharmacy current address

            $delivery_address = $customerAddDetails->address_line;

            $Pharmacy = Pharmacies::where('user_id', $request->pharmacy_id)->first();

            if ($Pharmacy) {
                $selected_pharmacy_address = trim("{$Pharmacy->address},{$Pharmacy->city},{$Pharmacy->state},{$Pharmacy->pincode}");
            }

            $selected_pharmacy_latlong = trim("{$Pharmacy->latitude},{$Pharmacy->longitude}");

            $platfromfee = Additionalcharges::value('platfrom_fee') ?? 0;


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
                'user_id' => $userId,
                'pharmacy_id' => $request->pharmacy_id,
                'product_details' => $productData->medicine,  // pharmacy mdeicine se directly
                'items_price' => $productData->total_amount,
                'platform_fees' => $platfromfee,
                'delivery_charges' => $request->delivery_charge,
                'total_price' => $request->total_price,
                'delivery_address' => $delivery_address,
                'delivery_options' => $request->delivery_option,
                'add_patient' => $request->add_patient,
                'payment_option' => $request->payment_option,
                'status' => 0,
                'selected_pharmacy_address' => $selected_pharmacy_address,
                'selected_pharmacy_latlong' => $selected_pharmacy_latlong
            ]);


            Phrmacymedicine::where('customer_id', $userId)
                ->where('phrmacy_id', $request->pharmacy_id)
                ->update(['status' => 1]);

            // Set status = 2 where customer_id = 3 and pharmacy_id is not 20
            Phrmacymedicine::where('customer_id', $userId)
                ->where('phrmacy_id', '!=', $request->pharmacy_id)
                ->update(['status' => 2]);


            // Cart clear karo
            //    Carts::where('customer_id', $userId)->delete();

            return response()->json([
                'status' => true,
                'message' => 'Order placed successfully',
                'order_id' => $order->order_id
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
