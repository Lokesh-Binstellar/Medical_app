<?php

namespace App\Http\Controllers;

use App\Events\MyEvent;
use App\Models\Carts;
use App\Models\CustomerAddress;
use App\Models\Customers;
use App\Models\Pharmacies;
use App\Models\RequestQuote;
use App\Models\User;
use App\Notifications\QuoteRequested;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;

class RequestQuoteController extends Controller
{
    //view all Notification

   public function index()
{
    $user = auth()->user();

    $unread = $user->unreadNotifications;
    $read = $user->readNotifications;

    $formattedUnread = $unread->map(function ($not) {
        return [
            'title' => $not->data['title'] ?? 'Notification',
            'message' => $not->data['message'] ?? '',
            'datetime' => \Carbon\Carbon::parse($not->created_at)->format('d M Y, h:i A'),
        ];
    });

    $formattedRead = $read->map(function ($not) {
        return [
            'title' => $not->data['title'] ?? 'Notification',
            'message' => $not->data['message'] ?? '',
            'datetime' => \Carbon\Carbon::parse($not->created_at)->format('d M Y, h:i A'),
        ];
    });

    return view('view_notifications.index', compact('formattedUnread', 'formattedRead', 'user'));
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

        if (!$address) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'address not found.',
                ],
                404,
            );
        }

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

        $pharmacies = Pharmacies::where('status', 1)->get();


        foreach ($pharmacies as $pharmacy) {
            if ($pharmacy->latitude && $pharmacy->longitude) {
                $distance = $this->getRoadDistance($address->lat, $address->lng, $pharmacy->latitude, $pharmacy->longitude, $apiKey);

                if ($distance !== false && $distance <= 10) {
                    $pharmacy->road_distance_km = round($distance, 2);
                    $nearby[] = $pharmacy;
                }
            }
        }
        if (empty($nearby)) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'No pharmacy found within 10 km radius. please select different address',
                ],
                404,
            );
        }

        
        foreach ($nearby as $pharmacy) {
            $exists = RequestQuote::where('customer_id', $userId)->where('pharmacy_id', $pharmacy->user_id)->exists();

            $pharmacyUser = User::where('id', $pharmacy->user_id)->first();
            $customer = Customers::find($userId); // assuming $userId is the customer
            $pharmacyUser->notify(new QuoteRequested($customer));

            //Event call for refresh the page
            event(new MyEvent('pharmacy', $pharmacyUser->id,'You have received a new quote.' ));

            // Get the user's latest cart
            $cart = Carts::where('customer_id', $userId)->latest()->first();
            
            // Extract prescription_id (removing array brackets if needed)
            $prescriptionId = null;
            if ($cart && !empty($cart->prescription_id)) {
                // Handle cases where prescription_id is stored as ["63"]
                $prescriptionArray = json_decode($cart->prescription_id, true);
                $prescriptionId = is_array($prescriptionArray) ? $prescriptionArray[0] : $cart->prescription_id;
            }

            // Prepare products details (exactly as stored in cart)
            $productsDetails = [];
            if ($cart && !empty($cart->products_details)) {
                $productsDetails = json_decode($cart->products_details, true);
            }
            //echo "sdfs"; print_r($prescriptionId);die;

            if (!$exists) {
                RequestQuote::create([
                    'customer_id' => $userId,
                    'pharmacy_id' => $pharmacy->user_id,
                    'customer_address' => json_encode([
                        'type' => $addressType,
                        'lat' => $address->lat,
                        'lng' => $address->lng,
                    ]),
                    'prescription_id' => json_encode($prescriptionId),
                    'products_details' => json_encode($productsDetails), // Already formatted correctly
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
            'status' => true,
        ]);
    }

    public function markAsRead($id)
    {
        $notification = DatabaseNotification::find($id);

        if ($notification && $notification->notifiable_id == Auth::user()->pharmacies->user_id) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }
        // event(new MyEvent('remove'));
        return response()->json(['success' => false], 404);
    }
}
