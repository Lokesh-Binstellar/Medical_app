<?php

namespace App\Http\Controllers;

use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class CustomerAddressController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userId = $request->get('user_id');
        if ($request->filled('latlng')) {
            // print_r("lat long present");die;

            $latlng = $request->latlng;
            $apiKey = env('GOOGLE_MAPS_API_KEY');

            $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$latlng&key=$apiKey";

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                echo 'Curl error: ' . curl_error($ch);
            } else {
                $data = json_decode($response, true);
                if (empty($data['results'])) {
                    return response([
                        'status' => false,
                        'message' => 'latlong not found',
                    ]);
                }
                // Initialize values
                $city = null;
                $postalCode = null;
                $state = null;
                $formatted_address = null;

                //print_r($data);die;
                // Parse address components
                if (!empty($data['results'])) {
                    foreach ($data['results'][0]['address_components'] as $component) {
                        if (in_array('administrative_area_level_3', $component['types'])) {
                            $city = $component['long_name'];
                            // echo $lat;die;
                            // print_r($city);
                            // die;
                        }

                        if (in_array('administrative_area_level_1', $component['types'])) {
                            $state = $component['long_name'];
                        }
                        if (in_array('postal_code', $component['types'])) {
                            $postalCode = $component['long_name'];
                            //echo "Formatted Address: $postalCode\n"; die;
                        }
                    }

                    if ($request->filled('postal_code')) {
                        $postalCode = $request->postal_code;
                    }

                    $formatted_address = $data['results'][0]['formatted_address'];
                    if ($request->filled('house_number')) {
                        $formatted_address = trim($request->house_number) . ', ' . $formatted_address;
                    }

                    $lat = $data['results'][0]['geometry']['location']['lat'];
                    $lng = $data['results'][0]['geometry']['location']['lng'];
                }

                if (empty($city) || empty($state) || empty($formatted_address)) {
                    return response([
                        'status' => false,
                        'message' => ' not found',
                    ]);
                }
                //echo '<pre>'; print_r(   $request->latlng); die;
                $this->saveAddress($city, $postalCode, $userId, $request, $state, $formatted_address, $lat, $lng);
            }
            curl_close($ch);
        } elseif ($request->filled('address_line')) {
            $address_line = urlencode($request->address_line);
            $apiKey = env('GOOGLE_MAPS_API_KEY');

            $url = "https://maps.googleapis.com/maps/api/geocode/json?address=$address_line&key=$apiKey";

            //echo $url;die;
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                echo 'Curl error: ' . curl_error($ch);
            } else {
                $data = json_decode($response, true);
                if (empty($data['results'])) {
                    return response([
                        'status' => false,
                        'message' => 'address not found',
                    ]);
                }

                // Initialize values
                $city = null;
                $postalCode = null;
                $state = null;

                // Parse address components
                if (!empty($data['results'])) {
                    foreach ($data['results'][0]['address_components'] as $component) {
                        if (in_array('administrative_area_level_3', $component['types'])) {
                            $city = $component['long_name'];
                        }
                        if (in_array('administrative_area_level_1', $component['types'])) {
                            $state = $component['long_name'];
                        }
                        if (in_array('postal_code', $component['types'])) {
                            $postalCode = $component['long_name'];
                        }
                        if (in_array('postal_code', $component['types'])) {
                            $postalCode = $component['long_name'];
                        }
                    }

                    if ($request->filled('postal_code')) {
                        $postalCode = $request->postal_code;
                    }

                    $formatted_address = $data['results'][0]['formatted_address'];
                    if ($request->filled('house_number')) {
                        $formatted_address = trim($request->house_number) . ', ' . $formatted_address;
                    }
                    $lat = $data['results'][0]['geometry']['location']['lat'];
                    $lng = $data['results'][0]['geometry']['location']['lng'];
                    // echo $lat;die;
                }
                if (empty($city) || empty($state) || empty($formatted_address)) {
                    return response([
                        'status' => false,
                        'message' => ' not found',
                    ]);
                }

                $this->saveAddress($city, $postalCode, $userId, $request, $state, $formatted_address, $lat, $lng);
            }
        } elseif ($request->filled('postal_code')) {
            $postalCode = $request->postal_code;
            $apiKey = env('GOOGLE_MAPS_API_KEY');

            $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($postalCode) . "&key=$apiKey";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                curl_close($ch);
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Curl error: ' . curl_error($ch),
                    ],
                    500,
                );
            }

            curl_close($ch); // ✅ Close after error check

            $data = json_decode($response, true);

            if (empty($data['results'])) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'No results found for postal code',
                    ],
                    404,
                );
            }

            $addressComponents = $data['results'][0]['address_components'];
            $formatted_address = $data['results'][0]['formatted_address'];

            // ✅ Add house number if provided
            if ($request->filled('house_number')) {
                $formatted_address = trim($request->house_number) . ', ' . $formatted_address;
            }

            // ✅ Ensure postal code is included in formatted address
            if (!str_contains($formatted_address, $postalCode)) {
                $formatted_address .= ', ' . $postalCode;
            }

            $lat = $data['results'][0]['geometry']['location']['lat'];
            $lng = $data['results'][0]['geometry']['location']['lng'];

            $city = $state = null;

            // ✅ Extract city/state from components
            foreach ($addressComponents as $component) {
                if (in_array('locality', $component['types']) && !$city) {
                    $city = $component['long_name'];
                } elseif (in_array('administrative_area_level_2', $component['types']) && !$city) {
                    $city = $component['long_name'];
                } elseif (in_array('administrative_area_level_3', $component['types']) && !$city) {
                    $city = $component['long_name'];
                } elseif (in_array('sublocality', $component['types']) && !$city) {
                    $city = $component['long_name'];
                } elseif (in_array('neighborhood', $component['types']) && !$city) {
                    $city = $component['long_name'];
                }

                if (in_array('administrative_area_level_1', $component['types'])) {
                    $state = $component['long_name'];
                }

                if (in_array('postal_code', $component['types'])) {
                    $postalCode = $component['long_name'];
                }
            }

            if (empty($city) || empty($state) || empty($formatted_address)) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Incomplete location data from postal code',
                    ],
                    422,
                );
            }

            return $this->saveAddress($city, $postalCode, $userId, $request, $state, $formatted_address, $lat, $lng);
        } else {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'No valid location data provided',
                ],
                400,
            );
        }

        return response()->json([
            'status' => true,
            'data' => [
                'city' => $city,
                'state' => $state,
                'postal_code' => $postalCode,
                'formatted_address' => $formatted_address,
                'lat' => $lat,
                'lng' => $lng,
            ],
        ]);
    }

    public function saveAddress($city, $postalCode, $userId, $request, $state, $formatted_address, $lat, $lng)
    {
        // $validator = Validator::make($request->all(), [
        //     'name' => 'required|nullable',
        //     'mobile_no' => 'required|nullable',
        //     'address_type' => 'required|in:home,work,other',
        //     'house_number' => 'required|nullable',
        //     'latlng' => 'required_without:address_line|string|nullable',
        //     'address_line' => 'required_without:latlng|string|nullable',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'status' => false,
        //         'errors' => $validator->errors()
        //     ], 422);
        // }

        // Check if user already has this address_type
        $existing = CustomerAddress::where('customer_id', $userId)->where('address_type', $request->address_type)->first();

        if ($existing) {
            // Update existing address
            $existing->update([
                'name' => $request->name,
                'mobile_no' => $request->mobile_no,
                'house_number' => $request->house_number,
                'address_line' => $formatted_address,
                'lat' => $lat,
                'lng' => $lng,
                'city' => $city,
                'state' => $state,
                'postal_code' => $postalCode,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Address updated successfully',
                'data' => $existing,
            ]);
        } else {
            // Create new address
            $address = CustomerAddress::create([
                'customer_id' => $userId,
                'name' => $request->name,
                'mobile_no' => $request->mobile_no,
                'address_type' => $request->address_type,
                'house_number' => $request->house_number,
                'address_line' => $formatted_address,
                'lat' => $lat,
                'lng' => $lng,
                'city' => $city,
                'state' => $state,
                'postal_code' => $postalCode,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Address saved successfully',
                'data' => $address,
            ]);
        }
    }

    public function getAddress(Request $request)
    {
        $userId = $request->get('user_id');
        if (!$userId) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'User ID is required.',
                ],
                400,
            );
        }
        $addresses = CustomerAddress::where('customer_id', $userId)->get();

        if ($addresses->isEmpty()) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'No address found for this user.',
                ],
                404,
            );
        }
        $filteredAddresses = $addresses->map(function ($addresses) {
            return [
                'customer_id' => $addresses->customer_id,
                'name' => $addresses->name,
                'mobile_no' => $addresses->mobile_no,
                'address_type' => $addresses->address_type,
                'house_number' => $addresses->house_number,
                'address_line' => $addresses->address_line,
                'lat' => $addresses->lat,
                'lng' => $addresses->lng,
                'city' => $addresses->city,
                'state' => $addresses->state,
                'postal_code' => $addresses->postal_code,
            ];
        });

        return response()->json([
            'status' => true,
            'data' => $filteredAddresses,
        ]);
    }

    public function deleteAddress(Request $request)
    {
        $userId = $request->get('user_id');
        // echo   $userId;die;
        $addressType = $request->get('address_type');

        if (!$userId || !$addressType) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'User ID and address type are required',
                ],
                400,
            );
        }

        $address = CustomerAddress::where('customer_id', $userId)->where('address_type', $addressType)->first();

        if (!$address) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Address not found',
                ],
                404,
            );
        }

        $address->delete();

        return response()->json([
            'status' => true,
            'message' => 'Address deleted successfully',
        ]);
    }

    public function getAddressFromLatLng(Request $request)
    {
        if (!$request->filled('latlng')) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'latlng is required',
                ],
                400,
            );
        }

        $latlng = $request->latlng;
        $apiKey = env('GOOGLE_MAPS_API_KEY');
        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$latlng&key=$apiKey";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            curl_close($ch);
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Curl error: ' . curl_error($ch),
                ],
                500,
            );
        }

        curl_close($ch);

        $data = json_decode($response, true);

        if (empty($data['results'])) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'No results found for latlng',
                ],
                404,
            );
        }

        // Extract data
        $components = $data['results'][0]['address_components'];
        $formatted_address = $data['results'][0]['formatted_address'];
        $lat = $data['results'][0]['geometry']['location']['lat'];
        $lng = $data['results'][0]['geometry']['location']['lng'];

        $city = $state = $postalCode = null;

        foreach ($components as $component) {
            if (in_array('administrative_area_level_3', $component['types']) && !$city) {
                $city = $component['long_name'];
            } elseif (in_array('locality', $component['types']) && !$city) {
                $city = $component['long_name'];
            } elseif (in_array('administrative_area_level_2', $component['types']) && !$city) {
                $city = $component['long_name'];
            }

            if (in_array('administrative_area_level_1', $component['types'])) {
                $state = $component['long_name'];
            }

            if (in_array('postal_code', $component['types'])) {
                $postalCode = $component['long_name'];
            }
        }

        return response()->json([
            'status' => true,
            'data' => [
                'city' => $city,
                'state' => $state,
                'postal_code' => $postalCode,
                'formatted_address' => $formatted_address,
                'lat' => $lat,
                'lng' => $lng,
            ],
        ]);
    }

    //seggetion api
    public function placeAutocomplete(Request $request)
    {
        $input = $request->query('input');

        if (empty($input)) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Input parameter is required.',
                ],
                400,
            );
        }

        $apiKey = env('GOOGLE_MAPS_API_KEY');

        $url = 'https://maps.googleapis.com/maps/api/place/autocomplete/json';

        $response = Http::get($url, [
            'input' => $input,
            'components' => 'country:in',
            'key' => $apiKey,
        ]);

        if ($response->failed()) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Google API request failed.',
                ],
                500,
            );
        }

        $data = $response->json();

        // Return predictions only
        return response()->json([
            'status' => true,
            'suggestions' => $data['predictions'] ?? [],
        ]);
    }
}
