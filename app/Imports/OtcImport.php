<?php

namespace App\Imports;

use App\Models\Otcmedicine;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OtcImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        ini_set('max_execution_time', 0); // optional: avoid timeout

        $savedImages = [];

        // ✅ Download and save all valid image URLs
        if (!empty($row['final_urls'])) {
            $urls = explode('|', $row['final_urls']);

            foreach ($urls as $url) {
                $url = trim($url);
                if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) continue;

                $fileName = basename(parse_url($url, PHP_URL_PATH));
                $relativePath = 'medicines/' . $fileName;

                if (!Storage::disk('public')->exists($relativePath)) {
                    try {
                        $fileContents = Http::get($url)->body();
                        Storage::disk('public')->put($relativePath, $fileContents);
                    } catch (\Exception $e) {
                        \Log::error("Image download failed: $url - " . $e->getMessage());
                        continue;
                    }
                }

                $savedImages[] = $relativePath;
            }
        }

        // ✅ Extract only the main category from breadcrumbs
        $mainCategory = null;
        if (!empty($row['breadcrumbs'])) {
            $parts = explode(' > ', $row['breadcrumbs']);
            $mainCategory = isset($parts[1]) ? trim($parts[1]) : null;
        }
// echo $row['id'];
// die();
        /* return new Otcmedicine([
            'otc_id'               => $row['id'],
            'name'                 => $row['name'],
            'breadcrumbs'          => $row['breadcrumbs'], // <-- only main category here
            'manufacturers'        => $row['manufacturers'],
            'type'                 => $row['type'],
            'packaging'            => $row['packaging'],
            'package'              => $row['package'],
            'qty'                  => $row['qty'],
            'product_form'         => $row['product_form'],
            'mrp'                  => $row['mrp'],
            'product_highlights'   => $row['product_highlights'],
            'information'          => $row['information'],
            'key_ingredients'      => $row['key_ingredients'],
            'key_benefits'         => $row['key_benefits'],
            'directions_for_use'   => $row['directions_for_use'],
            'safety_information'   => $row['safety_information'],
            'manufacturer_address' => $row['manufacturer_address'],
            'country_of_origin'    => $row['country_of_origin'],
            'manufacturer_details' => $row['manufacturer_details'],
            'marketer_details'     => $row['marketer_details'],
            'image_url'            => implode(',', $savedImages),
            'category' => $mainCategory
        ]); */

        if ($row['id'] != '') {
            # code...
            $data = [
                'otc_id'               => $row['id'],
                'name'                 => $row['name'],
                'breadcrumbs'          => $row['breadcrumbs'], // <-- only main category here
                'manufacturers'        => $row['manufacturers'],
                'type'                 => $row['type'],
                'packaging'            => $row['packaging'],
                'package'              => $row['package'],
                'qty'                  => $row['qty'],
                'product_form'         => $row['product_form'],
                'mrp'                  => $row['mrp'],
                'product_highlights'   => $row['product_highlights'],
                'information'          => $row['information'],
                'key_ingredients'      => $row['key_ingredients'],
                'key_benefits'         => $row['key_benefits'],
                'directions_for_use'   => $row['directions_for_use'],
                'safety_information'   => $row['safety_information'],
                'manufacturer_address' => $row['manufacturer_address'],
                'country_of_origin'    => $row['country_of_origin'],
                'manufacturer_details' => $row['manufacturer_details'],
                'marketer_details'     => $row['marketer_details'],
                'image_url'            => implode(',', $savedImages),
                'category' => $mainCategory
            ];
    
            $create = Otcmedicine::create($data);
            return $create;
        }
        // dd($create);


    }
}
