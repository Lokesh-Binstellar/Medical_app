<?php

namespace App\Imports;

use App\Models\Janaushadhi;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class janushadhiImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // dd($row);
        return Janaushadhi::Create(
            [
                'drug_code' => $row['drug_code'],
                'generic_name' => $row['generic_name'],
                'unit_size' => $row['unit_size'],
                'mrp' => $row['mrp'],
                'group_name' => $row['group_name'],
            ]
        );
    }

    public function rules(): array{
        return[
            '*.drug_code' => ['required'],
            '*.generic_name' => ['required'],
            '*.unit_size' => ['required',],
            '*.mrp' => ['required'],
            '*.group_name' => ['required']
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
