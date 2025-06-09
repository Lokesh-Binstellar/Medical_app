<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pharmacies;
use App\Models\User;
use App\Models\CustomerAddress;
use App\Models\Customers;
use App\Models\Carts;
use App\Models\RequestQuote;
use App\Notifications\QuoteRequested;
use Illuminate\Support\Facades\Notification;
use App\Events\MyEvent;
use Illuminate\Support\Facades\Log;

class FireMyEventCommand extends Command
{
    protected $signature = 'auto:request-quotes';
    protected $description = 'Automatically send quote requests to nearby pharmacies via cron';

    public function handle()
    {
        $apiKey = env('GOOGLE_MAPS_API_KEY');

        $addresses = CustomerAddress::whereNotNull('lat')->whereNotNull('lng')->get();

        foreach ($addresses as $address) {
            $userId = $address->customer_id;
            $customer = Customers::find($userId);

            if (!$customer) {
                Log::warning("Customer not found for address ID: {$address->id}");
                continue;
            }

            $pharmacies = Pharmacies::where('status', 1)->get();
            $nearbyPharmacies = [];

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
                        $nearbyPharmacies[] = $pharmacy;
                    }
                }
            }

            if (empty($nearbyPharmacies)) {
                Log::info("No nearby pharmacies within 10km for customer ID: {$userId}");
                continue;
            }

            foreach ($nearbyPharmacies as $pharmacy) {
                $pharmacyUser = User::find($pharmacy->user_id);

                if (!$pharmacyUser) {
                    Log::warning("Pharmacy user not found for pharmacy ID: {$pharmacy->id}");
                    continue;
                }

                $alreadyExists = RequestQuote::where('customer_id', $userId)
                    ->where('pharmacy_id', $pharmacyUser->id)
                    ->exists();

                if ($alreadyExists) {
                    Log::info("Quote already exists for customer ID: {$userId} and pharmacy ID: {$pharmacyUser->id}");
                    continue;
                }

                // Get customer's latest cart
                $cart = Carts::where('customer_id', $userId)->latest()->first();

                $prescriptionId = null;
                if ($cart && !empty($cart->prescription_id)) {
                    $prescriptionArray = json_decode($cart->prescription_id, true);
                    $prescriptionId = is_array($prescriptionArray) ? $prescriptionArray[0] : $cart->prescription_id;
                }

                $productsDetails = [];
                if ($cart && !empty($cart->products_details)) {
                    $productsDetails = json_decode($cart->products_details, true);
                }

                // Create request quote
                RequestQuote::create([
                    'customer_id' => $userId,
                    'pharmacy_id' => $pharmacy->user_id,
                    'customer_address' => json_encode([
                        'type' => $address->address_type,
                        'lat' => $address->lat,
                        'lng' => $address->lng,
                    ]),
                    'prescription_id' => json_encode($prescriptionId),
                    'products_details' => json_encode($productsDetails),
                ]);

                // Send notification
                Notification::send($pharmacyUser, new QuoteRequested($customer));

                // Fire event
                event(new MyEvent('pharmacy', $pharmacyUser->id, 'You have received a new quote.'));

                // Final logging
                Log::info("✅ Quote sent | Customer ID: {$userId}, Pharmacy ID: {$pharmacyUser->id}");
                $this->info("✅ Quote sent | Customer ID: {$userId}, Pharmacy ID: {$pharmacyUser->id}");
            }
        }

        return Command::SUCCESS;
    }

    private function getRoadDistance($lat1, $lon1, $lat2, $lon2, $apiKey)
    {
        $url = "https://maps.googleapis.com/maps/api/directions/json?origin=$lat1,$lon1&destination=$lat2,$lon2&key=$apiKey";

        try {
            $response = @file_get_contents($url);
            $data = json_decode($response, true);

            if ($data && isset($data['routes'][0]['legs'][0]['distance']['value'])) {
                return $data['routes'][0]['legs'][0]['distance']['value'] / 1000;
            } else {
                Log::warning("Google Maps API failed: " . json_encode($data));
            }
        } catch (\Exception $e) {
            Log::error("Google Maps API error: " . $e->getMessage());
        }

        return false;
    }
}
