<?php

namespace App\Http\Controllers;

use App\Models\CustomerAddress;
use Illuminate\Http\Request;

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
        //echo '<pre>'; print_r(  $request->name); die;
        if ($request->filled('latlng')) {
            //print_r("lat long present");die;

            $latlng = $request->latlng;
            $apiKey = env('GOOGLE_MAPS_API_KEY');
            $apiKey = "AIzaSyD4USHslEOwk41ShOTx6fXPYbzTnoWxzTE";

            $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$latlng&key=$apiKey";

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                echo 'Curl error: ' . curl_error($ch);
            } else {
                $data = json_decode($response, true);

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
                    }
                    
                    $formatted_address = $data['results'][0]['formatted_address'];
                    
                }

                //echo "City (admin area level 3): " . ($city ?? 'Not found') . "\n";
                //echo "Postal Code: " . ($postalCode ?? 'Not found') . "\n";


                $this->saveAddress($city, $postalCode, $userId, $request, $state,$formatted_address);
            }

            curl_close($ch);
        } else {

            $address_line = urlencode($request->address_line);
            $apiKey = "AIzaSyD4USHslEOwk41ShOTx6fXPYbzTnoWxzTE";

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

                }

                $this->saveAddress($city, $postalCode, $userId, $request, $state,$formatted_address);
            }

            return response()->json([
                'message' => 'Address saved successfully'
            ]);
        }
    }

    public function saveAddress($city, $postalCode, $userId, $request, $state,$formatted_address)
    {
       
        // Create address
        $address = CustomerAddress::create([

            'customer_id' => $userId,
            'name' => $request->name,
            'mobile_no' => $request->mobile_no,
            'address_line' => $formatted_address,
            'city' => $city,
            'state' => $state,
            'postal_code' => $postalCode,
        ]);
    }


}
