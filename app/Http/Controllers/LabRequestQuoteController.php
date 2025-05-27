<?php

namespace App\Http\Controllers;

use App\Models\LabCart;
use App\Models\LabTest;
use App\Models\Rating;
use Illuminate\Http\Request;
use App\Models\Laboratories;
use App\Models\CustomerAddress;
use App\Models\Additionalcharges;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Carbon;

class LabRequestQuoteController extends Controller
{
    public function index()
    {
        $notifications =DatabaseNotification::all();

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
    public function searchlabs(Request $request)
    {
        $addressType = $request->input('address_type');
        $userId = $request->get('user_id');
        $apiKey = env('GOOGLE_MAPS_API_KEY');

        // Step 1: Check if tests exist in lab_carts for this user
        $cartTests = LabCart::where('customer_id', $userId)->pluck('test_details');

        if ($cartTests->isEmpty()) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'No tests found in cart for this customer.',
                ],
                404,
            );
        }

        // 1. Get user address
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

        $testIds = collect();
        foreach ($cartTests as $testGroup) {
            $decoded = json_decode($testGroup, true);
            if (is_array($decoded)) {
                $testIds = $testIds->merge(collect($decoded)->pluck('test_id'));
            }
        }
        $testIds = $testIds->unique()->values();

        // 3. Get test names from lab_tests
        $testNamesMap = LabTest::whereIn('id', $testIds)->pluck('name', 'id');

        // 4. Filter nearby labs and match tests
        $matchingLabs = [];

        $laboratories = Laboratories::all();

        foreach ($laboratories as $lab) {
            // Ensure both cities are available
            if (!empty($lab->city) && !empty($address->city)) {
                // Case-insensitive city comparison
                if (strcasecmp(trim($lab->city), trim($address->city)) === 0) {

                    $labTests = json_decode($lab->test, true);
            
                    if (!is_array($labTests)) {
                        continue;
                    }

                    $distance = null;
                    if ($lab->latitude && $lab->longitude) {
                        $distance = $this->getRoadDistance($address->lat, $address->lng, $lab->latitude, $lab->longitude, $apiKey);
                        if ($distance !== false) {
                            $distance = round($distance, 2);
                        } else {
                            $distance = null;
                        }
                    }

                    $matchedTests = collect($labTests)
                        ->filter(function ($labTest) use ($testIds) {
                            return isset($labTest['test']) && $testIds->contains($labTest['test']);
                        })
                        ->map(function ($labTest) use ($testNamesMap) {
                            $testId = $labTest['test'];

                            $originalPrice = (float) $labTest['price'];
                            $originalHomePrice = (float) ($labTest['homeprice'] ?? 0);
                            $offerVisitingPrice = isset($labTest['offer_visiting_price']) ? (float) $labTest['offer_visiting_price'] : null;
                            $offerHomePrice = isset($labTest['offer_home_price']) ? (float) $labTest['offer_home_price'] : null;

                            return array_filter([
                                'test_name' => $testNamesMap[$testId] ?? 'Unknown',
                                'original_price' => $originalPrice,
                                'original_homeprice' => $originalHomePrice,
                                'offer_visiting_price' => ($offerVisitingPrice !== null && $offerVisitingPrice !== $originalPrice) ? $offerVisitingPrice : null,
                                'offer_home_price' => ($offerHomePrice !== null && $offerHomePrice !== $originalHomePrice) ? $offerHomePrice : null,
                            ], function ($value) {
                                return $value !== null;
                            });
                        })
                        ->values();

                    // 🧮 Calculate totals
                    $totalPrice = $matchedTests->sum(function ($test) {
                        return isset($test['offer_visiting_price']) ? $test['offer_visiting_price'] : $test['original_price'];
                    });

                    $totalHomePrice = $matchedTests->sum(function ($test) {
                        return isset($test['offer_home_price']) ? $test['offer_home_price'] : $test['original_homeprice'];
                    });

                    $totalPickupCharge = $matchedTests->sum(function ($test) {
                        $price = isset($test['offer_visiting_price']) ? $test['offer_visiting_price'] : $test['original_price'];
                        $homeprice = isset($test['offer_home_price']) ? $test['offer_home_price'] : $test['original_homeprice'];
                        return $homeprice - $price;
                    });

                    $platformFee = Additionalcharges::value('platfrom_fee') ?? 0;
                    $totalPriceWithFee = $totalPrice + $platformFee;
                    $totalHomePriceWithFee = $totalHomePrice + $platformFee;

                    if ($matchedTests->isNotEmpty()) {
                        $ratings = Rating::where('rateable_id', $lab->user_id)
                            ->where('rateable_type', 'Laboratory')
                            ->pluck('rating');

                        $totalRatings = $ratings->count();
                        $rating = null;

                        if ($totalRatings > 0) {
                            $average = round($ratings->avg(), 1);
                            $rating = $average . " ($totalRatings)";
                        }

                        $matchingLabs[] = [
                            'lab_name' => $lab->lab_name,
                            'road_distance_km' => $distance, // not applicable when filtering by city
                            'matched_tests' => $matchedTests,
                            'rating' => $rating,
                            'nabl_iso_certified' => $lab->nabl_iso_certified == 1 ? 'Yes' : 'No',
                            'pickup' => $lab->pickup == 1 ? 'Yes' : 'No',
                            'total_price' => $totalPrice,
                            'total_homeprice' => $totalHomePrice,
                            'total_sample_pickup_charge' => $totalPickupCharge,
                            'platform_fee' => $platformFee,
                            'total_price_plus_platform_fee' => $totalPriceWithFee,
                            'total_homeprice_plus_platform_fee' => $totalHomePriceWithFee,
                        ];
                    }
                }
            }
        }


        // 5. Return response
        if (empty($matchingLabs)) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'No matching labs found within 10 km.',
                ],
                404,
            );
        }

        return response()->json([
            'status' => true,
            'labs' => $matchingLabs,
            'rating' => $rating,
        ]);
    }
}
