<?php

namespace App\Imports;

use App\TestClient;
use Illuminate\Validation\Rules\Unique;
use Illuminate\Validation\Rule;
// use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
// use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithValidation;

class ClientsImport implements ToModel, WithCustomCsvSettings, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // dd($row);
        return new TestClient([
            'name'=>@$row[0],
            'age'=>@$row[1],
            'phone'=>@$row[2],
            'address'=>@$row[3]
        ]);
    }

    public function rules(): array{
        return[
            '0' => 'unique:test_clients,name'
        ];
    }

    public function customValidationMessages()
    {
        return[
            '0.unique' => 'Name already exist, must be unique!'
        ];
    }

    public function getCsvSettings(): array
    {
        return [
            'input_encoding' => 'ISO-8859-1'
        ];
    }
}
