<?php

namespace App\Imports;

use App\Category;
use Illuminate\Validation\Rules\Unique;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class CategorysImport implements ToModel, WithValidation, WithCustomCsvSettings
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Category([
            'name'=>@$row[0],
        ]);
    }

    public function rules(): array{
        return[
            '0' => 'unique:categories,name'
        ];
    }

    public function customValidationMessages()
    {
        return[
            '0.unique' => 'category already exist, must be unique!'
        ];
    }

    public function getCsvSettings(): array
    {
        return [
            'input_encoding' => 'ISO-8859-1'
        ];
    }
}
