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

    //througn postal code get city and state

    public function getCityStateFromPostalCode(Request $request)
    {
        try {
            // Validate input
            $request->validate([
                'postal_code' => 'required|string|min:4|max:10',
            ]);

            $postalCode = $request->postal_code;
            $apiKey = env('GOOGLE_MAPS_API_KEY');

            if (!$apiKey) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Google Maps API key is not configured.',
                    ],
                    500,
                );
            }

            $url = 'https://maps.googleapis.com/maps/api/geocode/json';
            $response = Http::timeout(5)->get($url, [
                'address' => $postalCode,
                'components' => 'country:IN',
                'key' => $apiKey,
            ]);
// echo $response ;die;
            if ($response->failed()) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Google Maps API request failed.',
                        'error' => $response->body(), // Remove in production
                    ],
                    $response->status() ?: 500,
                );
            }

            $data = $response->json();

            if (empty($data['results'])) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'No results found for this postal code.',
                    ],
                    404,
                );
            }

            $components = $data['results'][0]['address_components'];

            $city = $state = null;

            foreach ($components as $component) {
                $types = $component['types'];

                if (in_array('locality', $types) || in_array('administrative_area_level_2', $types)) {
                    $city = $city ?? $component['long_name'];
                }

                if (in_array('administrative_area_level_1', $types)) {
                    $state = $component['long_name'];
                }
            }

            return response()->json(
                [
                    'status' => true,
                    'data' => [
                        'city' => $city,
                        'state' => $state,
                    ],
                ],
                200,
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Validation error.',
                    'errors' => $e->errors(),
                ],
                422,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'An unexpected error occurred.',
                    'error' => $e->getMessage(), // Remove or log in production
                ],
                500,
            );
        }
    }

   public function saveAddress(Request $request)
{
    $userId = $request->get('user_id');

    try {
        // Validation
        $request->validate([
            'name' => 'required|string|max:100',
            'mobile_no' => 'required|string|max:15',
            'address_type' => 'required|string|in:home,work,other',
            'house_number' => 'required|string|max:255',
            'area' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
        ]);

        $parts = [$request->house_number, $request->area, $request->city, $request->state, $request->postal_code];
        $filteredParts = array_filter($parts, fn($v) => !empty($v));
        $fullAddress = implode(', ', $filteredParts);

        // Google Maps API call
        $apiKey = env('GOOGLE_MAPS_API_KEY');
        if (!$apiKey) {
            return response()->json([
                'status' => false,
                'message' => 'Google Maps API key not configured.',
            ], 500);
        }

        $response = Http::timeout(5)->get('https://maps.googleapis.com/maps/api/geocode/json', [
            'address' => $fullAddress,
            'key' => $apiKey,
        ]);

        if ($response->failed()) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch coordinates from Google Maps.',
                'error' => $response->body(),
            ], 500);
        }

        $geoData = $response->json();
        if (empty($geoData['results'][0]['geometry']['location'])) {
            return response()->json([
                'status' => false,
                'message' => 'Unable to retrieve latitude/longitude for the address.',
            ], 404);
        }

        $location = $geoData['results'][0]['geometry']['location'];
        $lat = $location['lat'];
        $lng = $location['lng'];

        // Find existing address with same customer_id and address_type
        $address = CustomerAddress::where('customer_id', $userId)
            ->where('address_type', $request->address_type)
            ->first();

        if ($address) {
            // Address type already exists for this user — just return a message
            return response()->json([
                'status' => false,
                'message' => ucfirst($request->address_type) . ' address already exists .',
            
            ], 409); // 409 = Conflict
        }

        // Create new
        $address = CustomerAddress::create([
            'customer_id' => $userId,
            'name' => $request->name,
            'mobile_no' => $request->mobile_no,
            'address_type' => $request->address_type,
            'house_number' => $request->house_number,
            'area' => $request->area,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'lat' => $lat,
            'lng' => $lng,
            'address_line' => $fullAddress,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Address saved successfully.',
           
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'status' => false,
            'message' => 'Validation failed.',
            'errors' => $e->errors(),
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Something went wrong.',
            'error' => $e->getMessage(),
        ], 500);
    }
}

//updated or edit  address 

public function updateAddress(Request $request)
{
    $userId = $request->get('user_id');

    try {
        // Validation
        $request->validate([
            'name' => 'required|string|max:100',
            'mobile_no' => 'required|string|max:15',
            'address_type' => 'required|string|in:home,work,other',
            'house_number' => 'required|string|max:255',
            'area' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
        ]);

        // Step 1: Get the specific address record
        $address = CustomerAddress::where('customer_id', $userId)
            ->where('address_type', $request->address_type)
            ->first();

        if (!$address) {
            return response()->json([
                'status' => false,
                'message' => 'Address not found for this user and type.',
            ], 404);
        }

        // Step 2: Check if postal code changed
        $postalCodeChanged = $address->postal_code !== $request->postal_code;

        // Step 3: Prepare address string
        $parts = [$request->house_number, $request->area, $request->city, $request->state, $request->postal_code];
        $filteredParts = array_filter($parts, fn($v) => !empty($v));
        $fullAddress = implode(', ', $filteredParts);

        $lat = $address->lat;
        $lng = $address->lng;

        // Step 4: If postal code changed, get new coordinates
        if ($postalCodeChanged) {
            $apiKey = env('GOOGLE_MAPS_API_KEY');
            if (!$apiKey) {
                return response()->json([
                    'status' => false,
                    'message' => 'Google Maps API key not configured.',
                ], 500);
            }

            $response = Http::timeout(5)->get('https://maps.googleapis.com/maps/api/geocode/json', [
                'address' => $fullAddress,
                'key' => $apiKey,
            ]);

            if ($response->failed()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to fetch coordinates from Google Maps.',
                    'error' => $response->body(),
                ], 500);
            }

            $geoData = $response->json();
            if (empty($geoData['results'][0]['geometry']['location'])) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unable to retrieve latitude/longitude for the address.',
                ], 404);
            }

            $location = $geoData['results'][0]['geometry']['location'];
            $lat = $location['lat'];
            $lng = $location['lng'];
        }

        // Step 5: Update only the selected address_type
        $address->update([
            'name' => $request->name,
            'mobile_no' => $request->mobile_no,
            'house_number' => $request->house_number,
            'area' => $request->area,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'address_line' => $fullAddress,
            'lat' => $lat,
            'lng' => $lng,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Address updated successfully.',
            // 'data' => $address,
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'status' => false,
            'message' => 'Validation failed.',
            'errors' => $e->errors(),
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Something went wrong.',
            'error' => $e->getMessage(),
        ], 500);
    }
}






    // public function store(Request $request)
    // {
    //     $userId = $request->get('user_id');
    //     if ($request->filled('latlng')) {
    //         // print_r("lat long present");die;

    //         $latlng = $request->latlng;
    //         $apiKey = env('GOOGLE_MAPS_API_KEY');

    //         $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$latlng&key=$apiKey";

    //         $ch = curl_init();

    //         curl_setopt($ch, CURLOPT_URL, $url);
    //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    //         $response = curl_exec($ch);

    //         if (curl_errno($ch)) {
    //             echo 'Curl error: ' . curl_error($ch);
    //         } else {
    //             $data = json_decode($response, true);
    //             if (empty($data['results'])) {
    //                 return response([
    //                     'status' => false,
    //                     'message' => 'latlong not found',
    //                 ]);
    //             }
    //             // Initialize values
    //             $city = null;
    //             $postalCode = null;
    //             $state = null;
    //             $formatted_address = null;

    //             //print_r($data);die;
    //             // Parse address components
    //             if (!empty($data['results'])) {
    //                 foreach ($data['results'][0]['address_components'] as $component) {
    //                     if (in_array('administrative_area_level_3', $component['types'])) {
    //                         $city = $component['long_name'];
    //                         // echo $lat;die;
    //                         // print_r($city);
    //                         // die;
    //                     }

    //                     if (in_array('administrative_area_level_1', $component['types'])) {
    //                         $state = $component['long_name'];
    //                     }
    //                     if (in_array('postal_code', $component['types'])) {
    //                         $postalCode = $component['long_name'];
    //                         //echo "Formatted Address: $postalCode\n"; die;
    //                     }
    //                 }

    //                 if ($request->filled('postal_code')) {
    //                     $postalCode = $request->postal_code;
    //                 }

    //                 $formatted_address = $data['results'][0]['formatted_address'];
    //                 if ($request->filled('house_number')) {
    //                     $formatted_address = trim($request->house_number) . ', ' . $formatted_address;
    //                 }

    //                 $lat = $data['results'][0]['geometry']['location']['lat'];
    //                 $lng = $data['results'][0]['geometry']['location']['lng'];
    //             }

    //             if (empty($city) || empty($state) || empty($formatted_address)) {
    //                 return response([
    //                     'status' => false,
    //                     'message' => ' not found',
    //                 ]);
    //             }
    //             //echo '<pre>'; print_r(   $request->latlng); die;
    //             $this->saveAddress($city, $postalCode, $userId, $request, $state, $formatted_address, $lat, $lng);
    //         }
    //         curl_close($ch);
    //     } elseif ($request->filled('address_line')) {
    //         $address_line = urlencode($request->address_line);
    //         $apiKey = env('GOOGLE_MAPS_API_KEY');

    //         $url = "https://maps.googleapis.com/maps/api/geocode/json?address=$address_line&key=$apiKey";

    //         //echo $url;die;
    //         $ch = curl_init();

    //         curl_setopt($ch, CURLOPT_URL, $url);
    //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    //         $response = curl_exec($ch);

    //         if (curl_errno($ch)) {
    //             echo 'Curl error: ' . curl_error($ch);
    //         } else {
    //             $data = json_decode($response, true);
    //             if (empty($data['results'])) {
    //                 return response([
    //                     'status' => false,
    //                     'message' => 'address not found',
    //                 ]);
    //             }

    //             // Initialize values
    //             $city = null;
    //             $postalCode = null;
    //             $state = null;

    //             // Parse address components
    //             if (!empty($data['results'])) {
    //                 foreach ($data['results'][0]['address_components'] as $component) {
    //                     if (in_array('administrative_area_level_3', $component['types'])) {
    //                         $city = $component['long_name'];
    //                     }
    //                     if (in_array('administrative_area_level_1', $component['types'])) {
    //                         $state = $component['long_name'];
    //                     }
    //                     if (in_array('postal_code', $component['types'])) {
    //                         $postalCode = $component['long_name'];
    //                     }
    //                     if (in_array('postal_code', $component['types'])) {
    //                         $postalCode = $component['long_name'];
    //                     }
    //                 }

    //                 if ($request->filled('postal_code')) {
    //                     $postalCode = $request->postal_code;
    //                 }

    //                 $formatted_address = $data['results'][0]['formatted_address'];
    //                 if ($request->filled('house_number')) {
    //                     $formatted_address = trim($request->house_number) . ', ' . $formatted_address;
    //                 }
    //                 $lat = $data['results'][0]['geometry']['location']['lat'];
    //                 $lng = $data['results'][0]['geometry']['location']['lng'];
    //                 // echo $lat;die;
    //             }
    //             if (empty($city) || empty($state) || empty($formatted_address)) {
    //                 return response([
    //                     'status' => false,
    //                     'message' => ' not found',
    //                 ]);
    //             }

    //             $this->saveAddress($city, $postalCode, $userId, $request, $state, $formatted_address, $lat, $lng);
    //         }
    //     } elseif ($request->filled('postal_code')) {
    //         $postalCode = $request->postal_code;
    //         $apiKey = env('GOOGLE_MAPS_API_KEY');

    //         $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($postalCode) . "&key=$apiKey";

    //         $ch = curl_init();
    //         curl_setopt($ch, CURLOPT_URL, $url);
    //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    //         $response = curl_exec($ch);

    //         if (curl_errno($ch)) {
    //             curl_close($ch);
    //             return response()->json(
    //                 [
    //                     'status' => false,
    //                     'message' => 'Curl error: ' . curl_error($ch),
    //                 ],
    //                 500,
    //             );
    //         }

    //         curl_close($ch); // ✅ Close after error check

    //         $data = json_decode($response, true);

    //         if (empty($data['results'])) {
    //             return response()->json(
    //                 [
    //                     'status' => false,
    //                     'message' => 'No results found for postal code',
    //                 ],
    //                 404,
    //             );
    //         }

    //         $addressComponents = $data['results'][0]['address_components'];
    //         $formatted_address = $data['results'][0]['formatted_address'];

    //         // ✅ Add house number if provided
    //         if ($request->filled('house_number')) {
    //             $formatted_address = trim($request->house_number) . ', ' . $formatted_address;
    //         }

    //         // ✅ Ensure postal code is included in formatted address
    //         if (!str_contains($formatted_address, $postalCode)) {
    //             $formatted_address .= ', ' . $postalCode;
    //         }

    //         $lat = $data['results'][0]['geometry']['location']['lat'];
    //         $lng = $data['results'][0]['geometry']['location']['lng'];

    //         $city = $state = null;

    //         // ✅ Extract city/state from components
    //         foreach ($addressComponents as $component) {
    //             if (in_array('locality', $component['types']) && !$city) {
    //                 $city = $component['long_name'];
    //             } elseif (in_array('administrative_area_level_2', $component['types']) && !$city) {
    //                 $city = $component['long_name'];
    //             } elseif (in_array('administrative_area_level_3', $component['types']) && !$city) {
    //                 $city = $component['long_name'];
    //             } elseif (in_array('sublocality', $component['types']) && !$city) {
    //                 $city = $component['long_name'];
    //             } elseif (in_array('neighborhood', $component['types']) && !$city) {
    //                 $city = $component['long_name'];
    //             }

    //             if (in_array('administrative_area_level_1', $component['types'])) {
    //                 $state = $component['long_name'];
    //             }

    //             if (in_array('postal_code', $component['types'])) {
    //                 $postalCode = $component['long_name'];
    //             }
    //         }

    //         if (empty($city) || empty($state) || empty($formatted_address)) {
    //             return response()->json(
    //                 [
    //                     'status' => false,
    //                     'message' => 'Incomplete location data from postal code',
    //                 ],
    //                 422,
    //             );
    //         }

    //         return $this->saveAddress($city, $postalCode, $userId, $request, $state, $formatted_address, $lat, $lng);
    //     } else {
    //         return response()->json(
    //             [
    //                 'status' => false,
    //                 'message' => 'No valid location data provided',
    //             ],
    //             400,
    //         );
    //     }

    //     return response()->json([
    //         'status' => true,
    //         'data' => [
    //             'city' => $city,
    //             'state' => $state,
    //             'postal_code' => $postalCode,
    //             'formatted_address' => $formatted_address,
    //             'lat' => $lat,
    //             'lng' => $lng,
    //         ],
    //     ]);
    // }

    // public function saveAddress($city, $postalCode, $userId, $request, $state, $formatted_address, $lat, $lng)
    // {
    //     // $validator = Validator::make($request->all(), [
    //     //     'name' => 'required|nullable',
    //     //     'mobile_no' => 'required|nullable',
    //     //     'address_type' => 'required|in:home,work,other',
    //     //     'house_number' => 'required|nullable',
    //     //     'latlng' => 'required_without:address_line|string|nullable',
    //     //     'address_line' => 'required_without:latlng|string|nullable',
    //     // ]);

    //     // if ($validator->fails()) {
    //     //     return response()->json([
    //     //         'status' => false,
    //     //         'errors' => $validator->errors()
    //     //     ], 422);
    //     // }

    //     // Check if user already has this address_type
    //     $existing = CustomerAddress::where('customer_id', $userId)->where('address_type', $request->address_type)->first();

    //     if ($existing) {
    //         // Update existing address
    //         $existing->update([
    //             'name' => $request->name,
    //             'mobile_no' => $request->mobile_no,
    //             'house_number' => $request->house_number,
    //             'address_line' => $formatted_address,
    //             'lat' => $lat,
    //             'lng' => $lng,
    //             'city' => $city,
    //             'state' => $state,
    //             'postal_code' => $postalCode,
    //         ]);

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Address updated successfully',
    //             'data' => $existing,
    //         ]);
    //     } else {
    //         // Create new address
    //         $address = CustomerAddress::create([
    //             'customer_id' => $userId,
    //             'name' => $request->name,
    //             'mobile_no' => $request->mobile_no,
    //             'address_type' => $request->address_type,
    //             'house_number' => $request->house_number,
    //             'address_line' => $formatted_address,
    //             'lat' => $lat,
    //             'lng' => $lng,
    //             'city' => $city,
    //             'state' => $state,
    //             'postal_code' => $postalCode,
    //         ]);

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Address saved successfully',
    //             'data' => $address,
    //         ]);
    //     }
    // }

    public function getAddress(Request $request)
    {
        $userId = $request->get('user_id');
        // echo   $userId
        try {
          


            // Fetch addresses for the given user
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

            // Format the addresses
            $formattedAddresses = $addresses->map(function ($address) {
                return [
                    // 'customer_id' => $address->customer_id,
                    'name' => $address->name,
                    'mobile_no' => $address->mobile_no,
                    'address_type' => $address->address_type,
                    'house_number' => $address->house_number,
                    'address_line' => $address->address_line,
                    'lat' => $address->lat,
                    'lng' => $address->lng,
                    'city' => $address->city,
                    'state' => $address->state,
                    'area'=> $address->area,
                    'postal_code' => $address->postal_code,
                ];
            });

            return response()->json(
                [
                    'status' => true,
                    'data' => $formattedAddresses,
                ],
                200,
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Validation error.',
                    'errors' => $e->errors(),
                ],
                422,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'An error occurred while fetching addresses.',
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
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

  public function getUserSelectLocation(Request $request)
{
    try {
        // Validate input
        $request->validate([
            'latlng' => 'nullable|string|regex:/^-?\d+(\.\d+)?,-?\d+(\.\d+)?$/',
            'address' => 'nullable|string|max:255',
        ]);

        $latlng = $request->latlng;
        $address = $request->address;
        $apiKey = env('GOOGLE_MAPS_API_KEY');

        if (!$apiKey) {
            return response()->json([
                'status' => false,
                'message' => 'Google Maps API key is not configured.',
            ], 500);
        }

        //CASE 1: latlng is provided
        if ($latlng) {
            $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$latlng}&key={$apiKey}";
        }

        // ----------- CASE 2: address is provided --------------
        elseif ($address) {
            $encodedAddress = urlencode($address);
            $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$encodedAddress}&key={$apiKey}";
        }

        // ----------- Neither address nor latlng --------------
        else {
            return response()->json([
                'status' => false,
                'message' => 'Either latlng or address is required.',
            ], 400);
        }

        // ----------- Call Google API --------------
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
        ]);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            return response()->json([
                'status' => false,
                'message' => 'Curl error: ' . $error,
            ], 500);
        }

        curl_close($ch);
        $data = json_decode($response, true);

        if (!isset($data['results']) || empty($data['results'])) {
            return response()->json([
                'status' => false,
                'message' => 'No results found for the given input.',
            ], 404);
        }

        $result = $data['results'][0];
        $components = $result['address_components'];
        $formattedAddress = $result['formatted_address'];
        $lat = $result['geometry']['location']['lat'];
        $lng = $result['geometry']['location']['lng'];

        $city = $state = $postalCode = null;

        foreach ($components as $component) {
            $types = $component['types'];
            if (in_array('locality', $types) || in_array('administrative_area_level_2', $types) || in_array('administrative_area_level_3', $types)) {
                $city = $city ?? $component['long_name'];
            }
            if (in_array('administrative_area_level_1', $types)) {
                $state = $component['long_name'];
            }
            if (in_array('postal_code', $types)) {
                $postalCode = $component['long_name'];
            }
        }

        return response()->json([
            'status' => true,
            'data' => [
                'city' => $city,
                'state' => $state,
                'postal_code' => $postalCode,
                'formatted_address' => $formattedAddress,
                'lat' => $lat,
                'lng' => $lng,
            ],
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'status' => false,
            'message' => 'Validation error.',
            'errors' => $e->errors(),
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'An error occurred while processing the request.',
            'error' => $e->getMessage(),
        ], 500);
    }
}


    //seggetion api
    public function placeAutocomplete(Request $request)
    {
        try {
            // Validate the input
            $request->validate([
                'input' => 'required|string|min:1',
            ]);

            $input = $request->query('input');
            $apiKey = env('GOOGLE_MAPS_API_KEY');

            if (!$apiKey) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Google Maps API key is not configured.',
                    ],
                    500,
                );
            }

            $url = 'https://maps.googleapis.com/maps/api/place/autocomplete/json';

            $response = Http::timeout(5)->get($url, [
                'input' => $input,
                'components' => 'country:in',
                'key' => $apiKey,
            ]);

            if ($response->failed()) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Google API request failed.',
                        'error' => $response->body(), // Optional: remove in production
                    ],
                    $response->status() ?: 500,
                );
            }

            $data = $response->json();

            if (!isset($data['predictions'])) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Invalid response from Google API.',
                    ],
                    500,
                );
            }

            // Extract only city, state, and description
            $filteredSuggestions = collect($data['predictions'])->map(function ($item) {
                $terms = $item['terms'];
                return [
                    'description' => $item['description'],
                    'city' => $terms[0]['value'] ?? null,
                    'state' => $terms[1]['value'] ?? null,
                ];
            });

            return response()->json(
                [
                    'status' => true,
                    'suggestions' => $filteredSuggestions,
                ],
                200,
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Validation error.',
                    'errors' => $e->errors(),
                ],
                422,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'An unexpected error occurred.',
                    'error' => $e->getMessage(), // Remove or log in production
                ],
                500,
            );
        }
    }
}
