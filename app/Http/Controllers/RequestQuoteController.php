<?php

namespace App\Http\Controllers;

use App\Models\CustomerAddress;
use App\Models\Pharmacies;
use App\Models\RequestQuote;
use App\Models\User;
use App\Notifications\QuoteRequested;
use Illuminate\Http\Request;

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
        $user = Pharmacies::find($userId);
    //    echo $user ;die;
        
        foreach ($nearby as $pharmacy) {
            $exists = RequestQuote::where('user_id', $userId)
                ->where('pharmacy_id', $pharmacy->user_id)
                ->exists();

            if (!$exists) {
                RequestQuote::create([
                    'user_id' => $userId,
                    'pharmacy_id' => $pharmacy->user_id,
                ]);

                $pharmacyUser = User::find($pharmacy->user_id);
                if ($pharmacyUser) {
                    $pharmacyUser->notify(new QuoteRequested($user));
                    echo ($pharmacyUser);
                    die;
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
