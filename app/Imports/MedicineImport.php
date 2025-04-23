<?php

namespace App\Imports;
ini_set('max_execution_time', 0);
use App\Models\Medicine;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MedicineImport implements ToModel, WithHeadingRow
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


        //echo '<pre>';print_r(value:   $savedImages);
        //die();
        return new Medicine([

            'product_id' => $row['product_id'],
            'product_name' => $row['product_name'],
            'marketer' => $row['marketer'],
            'salt_composition' => $row['salt_composition'],
            'medicine_type' => $row['medicine_type'],
            'introduction' => $row['introduction'],
            'benefits' => $row['benefits'],
            'description' => $row['description'],
            'how_to_use' => $row['how_to_use'],
            'safety_advise' => $row['safety_advise'],
            'if_miss' => $row['if_miss'],
            'packaging_detail' => $row['packaging_detail'],
            'package' => $row['package'],
            'qty' => $row['qty'],
            'product_form' => $row['product_form'],
            'prescription_required' => $row['prescription_required'],
            'fact_box' => $row['fact_box'],
            'primary_use' => $row['primary_use'],
            'storage' => $row['storage'],
            'use_of' => $row['use_of'],
            'common_side_effect' => $row['common_side_effect'],
            'alcohol_interaction' => $row['alcoholinteraction'],
            'pregnancy_interaction' => $row['pregnancyinteraction'],
            'lactation_interaction' => $row['lactationinteraction'],
            'driving_interaction' => $row['drivinginteraction'],
            'kidney_interaction' => $row['kidneyinteraction'],
            'liver_interaction' => $row['liverinteraction'],
            'manufacturer_address' => $row['manufacturer_address'],
            'country_of_origin' => $row['country_of_origin'],
            'q_a' => $row['q_a'],
            'how_it_works' => $row['how_it_works'],
            'interaction' => $row['interaction'],
            'manufacturer_details' => $row['manufacturer_details'],
            'marketer_details' => $row['marketer_details'],
            'image_url' => implode(",",$savedImages),
        ]);
        // unset($medicine->product_name); 
    }
}
