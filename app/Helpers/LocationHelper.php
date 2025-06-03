<?php
// app/Helpers/LocationHelper.php
namespace App\Helpers;

use Illuminate\Http\Request;

class LocationHelper
{
    public static function getCityofCurrentUser(Request $request)
    {
       $latlong = $request->latlong;
        if (!$latlong) {
            return response()->json(['status' => false, 'message' => 'latlong is required'], 200);
        }

        [$userLat, $userLon] = explode(',', $latlong);
        $userLat = trim($userLat);
        $userLon = trim($userLon);

        $apiKey = env('GOOGLE_MAPS_API_KEY');

        // 1. Get city from coordinates using Geocoding API
        $geoUrl = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$userLat,$userLon&key=$apiKey";
        $geoResponse = file_get_contents($geoUrl);
        $geoData = json_decode($geoResponse, true);

        if (!$geoData || $geoData['status'] !== 'OK') {
            return response()->json(['status' => false, 'message' => 'Could not determine city from location'], 400);
        }

        $city = null;
        foreach ($geoData['results'][0]['address_components'] as $component) {
            if (in_array('locality', $component['types'])) {
                $city = $component['long_name'];
                break;
            }
        }

        if (!$city) {
            return response()->json(['status' => false, 'message' => 'City not found in address data'], 400);
        }

        return $city;
    }
}
