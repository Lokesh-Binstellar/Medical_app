<?php

namespace App\Http\Controllers;

use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

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
                        "status" => false,
                        "message" => "latlong not found"

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
                        }

                        if (in_array('administrative_area_level_1', $component['types'])) {
                            $state = $component['long_name'];
                        }
                        if (in_array('postal_code', $component['types'])) {
                            $postalCode = $component['long_name'];
                        }
                    }

                    $formatted_address = $data['results'][0]['formatted_address'];
                    //  echo "Formatted Address: $formatted_address\n"; die;

                    $lat = $data['results'][0]['geometry']['location']['lat'];
                    $lng = $data['results'][0]['geometry']['location']['lng'];
                    // echo $lat;die;

                }

                //echo '<pre>'; print_r(   $request->latlng); die;
                $this->saveAddress($city, $postalCode, $userId, $request, $state, $formatted_address, $lat, $lng);
            }

            curl_close($ch);
        } else {

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
                        "status" => false,
                        "message" => "address not found"

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

                        // if (in_array('administrative_area_level_3', $component['types'])) {
                        //     $city = $component['long_name'];
                        // }

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
                    $formatted_address = $data['results'][0]['formatted_address'];
                    $lat = $data['results'][0]['geometry']['location']['lat'];
                    $lng = $data['results'][0]['geometry']['location']['lng'];
                    // echo $lat;die;


                }

                $this->saveAddress($city, $postalCode, $userId, $request, $state, $formatted_address, $lat, $lng);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Address saved successfully'
        ]);
    }

   public function saveAddress($city, $postalCode, $userId, $request, $state, $formatted_address, $lat, $lng)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'mobile_no' => 'required',
        'address_type' => 'required|in:home,work,other',
        'house_number' => 'required',
        'latlng' => 'required_without:address_line|string|nullable',
        'address_line' => 'required_without:latlng|string|nullable',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    // Check if user already has this address_type
    $existing = CustomerAddress::where('customer_id', $userId)
        ->where('address_type', $request->address_type)
        ->first();

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
            'data' => $existing
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
            'data' => $address
        ]);
    }
}
}
