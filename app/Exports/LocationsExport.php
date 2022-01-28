<?php

namespace App\Exports;

use App\Location;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class LocationsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Location::all();
    }

    public function headings(): array{
        return [
            'ID', 'State', 'Popular', 'Created', 'Updated', 'slug', 'Published'
        ];
    }
}
