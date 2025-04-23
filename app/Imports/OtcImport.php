<?php

namespace App\Imports;

use App\Models\Otcmedicine;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OtcImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $savedImages = [];

if (!empty($row['final_urls'])) {
    $urls = explode('|', $row['final_urls']);

    foreach ($urls as $url) {
        $url = trim($url); // clean spaces

        if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            continue;
        }

        // Get original filename from URL
        $fileName = basename(parse_url($url, PHP_URL_PATH));
        $relativePath = 'medicines/' . $fileName;

        // Check if the file already exists
        if (!Storage::disk('public')->exists($relativePath)) {
            try {
                $fileContents = Http::get($url)->body();
                Storage::disk('public')->put($relativePath, $fileContents);
            } catch (\Exception $e) {
                \Log::error("Image download failed: $url - " . $e->getMessage());
                continue;
            }
        }

        // Get public URL and add to array
        $fileUrl =$relativePath;
        $savedImages[] = $fileUrl;
    }

}

        return new Otcmedicine([
          
                'otc_id'               => $row['otc_id'],         
                'name'                 => $row['name'],
                'breadcrumbs'          => $row['breadcrumbs'],
                'manufacturers'        => $row['manufacturers'],
                'type'                 => $row['type'],
                'packaging'            => $row['packaging'],
                'package'              => $row['package'],
                'qty'                  => $row['qty'],
                'product_form'         => $row['product_form'],
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
            ]);
       
    }
}
