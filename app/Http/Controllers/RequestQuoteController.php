<?php

namespace App\Http\Controllers;

use App\Events\MyEvent;
use App\Models\CustomerAddress;
use App\Models\Customers;
use App\Models\Pharmacies;
use App\Models\RequestQuote;
use App\Models\User;
use App\Notifications\QuoteRequested;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class RequestQuoteController extends Controller
{


    public function getRoadDistance($lat1, $lon1, $lat2, $lon2, $apiKey)
    {
        $url = "https://maps.googleapis.com/maps/api/directions/json?origin=$lat1,$lon1&destination=$lat2,$lon2&key=$apiKey";

        $response = file_get_contents($url);
        $data = json_decode($response, true);

        if ($data['status'] === 'OK') {
            $distanceMeters = $data['routes'][0]['legs'][0]['distance']['value'];
            return $distanceMeters / 1000;
        }

        return false;
    }

    public function requestAQuote(Request $request)
    {
        
        $addressType = $request->input('address_type');
        
        $userId = $request->get('user_id');
        $apiKey = env('GOOGLE_MAPS_API_KEY');
        $address = CustomerAddress::where('customer_id', $userId)
        ->where('address_type', $addressType)
        ->first();
        
        if (!$address) {
            return response()->json([
                'status' => false,
                'message' => 'Work address not found.'
            ], 404);
        }
        
        if (!$address || !$address->lat || !$address->lng) {
            return response()->json([
                'status' => false,
                'message' => 'Customer location not found.'
            ], 404);
        }
        
        $nearby = [];
        
        $pharmacies = Pharmacies::all();
        
        foreach ($pharmacies as $pharmacy) {
            if ($pharmacy->latitude && $pharmacy->longitude) {
                $distance = $this->getRoadDistance(
                    $address->lat,
                    $address->lng,
                    $pharmacy->latitude,
                    $pharmacy->longitude,
                    $apiKey
                );
                
                if ($distance !== false && $distance <= 10) {
                    $pharmacy->road_distance_km = round($distance, 2);
                    $nearby[] = $pharmacy;
                }
            }
        }
        
        if (empty($nearby)) {
            return response()->json([
                'status' => false,
                'message' => 'No pharmacy found within 10 km radius.'
            ], 404);
        }
        foreach ($nearby as $pharmacy) {
            $exists = RequestQuote::where('customer_id', $userId)
            ->where('pharmacy_id', $pharmacy->user_id)
            ->exists();
            
            // echo  $userId ;
            //    die;
            // Get the user record for the pharmacy
            $pharmacyUser = User::where('id', $pharmacy->user_id)->first();
            // Get the user who is requesting the quote
            $requestingUser = CustomerAddress::find($userId);
            // dd($userId);
            $customer = Customers::find($userId); // assuming $userId is the customer
            $pharmacyUser->notify(new QuoteRequested($customer));
            
            event(new MyEvent('lokesh'));

            // Notification::send($pharmacyUser, new QuoteRequested($requestingUser));
            
            if (!$exists) {
                // Save quote request
                RequestQuote::create([
                    'customer_id' => $userId,
                    'pharmacy_id' => $pharmacy->user_id,
                ]);

                // Get the user record for the pharmacy
                $pharmacyUser = User::where('id', $pharmacy->user_id)->first();
                // Get the user who is requesting the quote
                $requestingUser = User::find($userId);
                
                dd($pharmacyUser);
                // Send notification
                if ($pharmacyUser && $requestingUser) {
                    // $pharmacyUser->notify(new QuoteRequested($requestingUser));
                    Notification::send($pharmacyUser, new QuoteRequested($requestingUser));
                }
                   
            }
        }




        $pharmacyIds = collect($nearby)->pluck('user_id');



        return response()->json([
            'status' => true
        ]);
        // Return only lat/lng
        // return response()->json([
        //     'status' => true,
        //     'lat' => $address->lat,
        //     'lng' => $address->lng
        // ]);

    }


 

}
