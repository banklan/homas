<?php

namespace App\Imports;

use App\Location;
use Illuminate\Validation\Rules\Unique;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class LocationsImport implements ToModel, WithValidation, WithCustomCsvSettings
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Location([
            'state'=>@$row[0],
            'is_popular'=>@$row[1]
        ]);
    }

    public function rules(): array{
        return[
            '0' => 'unique:locations,state'
        ];
    }

    public function customValidationMessages()
    {
        return[
            '0.unique' => 'State already exist, must be unique!'
        ];
    }

    public function getCsvSettings(): array
    {
        return [
            'input_encoding' => 'ISO-8859-1'
        ];
    }
}
