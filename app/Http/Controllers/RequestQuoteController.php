<?php

namespace App\Http\Controllers;

use App\Events\MyEvent;
use App\Models\CustomerAddress;
use App\Models\Customers;
use App\Models\Pharmacies;
use App\Models\RequestQuote;
use App\Models\User;
use App\Notifications\QuoteRequested;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
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

            $pharmacyUser = User::where('id', $pharmacy->user_id)->first();
            $customer = Customers::find($userId); // assuming $userId is the customer
            $pharmacyUser->notify(new QuoteRequested($customer));

            //Event call for refresh the page
            event(new MyEvent('message'));
            if (!$exists) {
                RequestQuote::create([
                    'customer_id' => $userId,
                    'pharmacy_id' => $pharmacy->user_id,
                ]);
                $pharmacyUser = User::where('id', $pharmacy->user_id)->first();
                $requestingUser = User::find($userId);

                // dd($pharmacyUser);
                // Send notification
                if ($pharmacyUser && $requestingUser) {
                    // $pharmacyUser->notify(new QuoteRequested($requestingUser));
                    Notification::send($pharmacyUser, new QuoteRequested($requestingUser));
                }

            }
        }
        return response()->json([
            'status' => true
        ]);
    }


public function markAsRead($id)
{
      $notification = DatabaseNotification::find($id);

    if ($notification && $notification->notifiable_id == Auth::user()->pharmacies->user_id) {
        $notification->markAsRead();
        return response()->json(['success' => true]);

    }
    event(new MyEvent('remove'));
    return response()->json(['success' => false], 404);
}







}
