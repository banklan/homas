<?php

namespace App\Exports;

use App\Service;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ServicesExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Service::all();
    }

    public function headings(): array{
        return [
            'ID', 'User ID', 'Category ID', 'Title', 'Position', 'Description', 'Experience Years',
            'Highlight', 'Keystrength', 'Views', 'Email', 'Phone', 'Address', 'City', 'Location ID',
            'Image', 'Website', 'Facebook', 'Instagram', 'Twitter', 'Approved', 'Featured', 'Premium',
            'Created', 'Updated', 'Slug', 'Published', 'Rating'
        ];
    }
}
