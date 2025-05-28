<?php

namespace App\Imports;

use App\Models\LabTest;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LabTestImport implements ToModel,WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // return new LabTest([
            if ($row['name'] != '') {

           $data=[
            'name' => $row['name'],
            'description' => $row['description'],
            'organ' => $row['organ'],
            'contains' => $row['contains'],
            'gender' => $row['gender'],
            // 'reports_in' => $row['reports_in'],
            'sample_required' => $row['sample_required'],
            'preparation' => $row['preparation'],
            'how_does_it_work' => $row['how_does_it_work'],
            'sub_reports' => $row['sub_reports'],
            'sub_report_details' => $row['sub_report_details'],
            'faq' => $row['faq'],
            'references' => $row['references'],

           ];
           $create = LabTest::create($data);
           return $create;
       }

        
    }
}
