<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laboratories;
use App\Models\CustomerAddress;

class LabRequestQuoteController extends Controller
{
    public function index()
    {
        $notifications = DatabaseNotification::all();

        $formattedNotifications = $notifications->map(function ($not) {
            $data = $not->data;
            return [
                'title' => $data['title'] ?? 'Notification',
                'message' => $data['message'] ?? '',
                'datetime' => Carbon::parse($not->created_at)->format('d M Y, h:i A'),
            ];
        });

        return view('view_notifications.index', compact('formattedNotifications'));
    }

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

        $address = CustomerAddress::where('customer_id', $userId)->where('address_type', $addressType)->first();

        if (!$address || !$address->lat || !$address->lng) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Customer location not found.',
                ],
                404,
            );
        }

        $nearby = [];

        // Replace Pharmacies with Laboratories model
        $laboratories = Laboratories::all();

        foreach ($laboratories as $lab) {
            if ($lab->latitude && $lab->longitude) {
                $distance = $this->getRoadDistance($address->lat, $address->lng, $lab->latitude, $lab->longitude, $apiKey);

                if ($distance !== false && $distance <= 10) {
                    $lab->road_distance_km = round($distance, 2);
                    $nearby[] = $lab;
                }
            }
        }

        if (empty($nearby)) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'No laboratory found within 10 km radius.',
                ],
                404,
            );
        }

        foreach ($nearby as $lab) {
            $exists = RequestQuote::where('customer_id', $userId)->where('laboratory_id', $lab->user_id)->exists();

            $labUser = User::where('id', $lab->user_id)->first();
            $customer = Customers::find($userId);

            // Notify lab user
            $labUser->notify(new QuoteRequested($customer));

            if (!$exists) {
                RequestQuote::create([
                    'customer_id' => $userId,
                    'laboratory_id' => $lab->user_id,
                    'customer_address' => json_encode([
                        'type' => $addressType,
                        'lat' => $address->lat,
                        'lng' => $address->lng,
                    ]),
                ]);

                $requestingUser = User::find($userId);

                if ($labUser && $requestingUser) {
                    Notification::send($labUser, new QuoteRequested($requestingUser));
                }
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Nearby laboratories notified successfully.',
        ]);
    }
}
