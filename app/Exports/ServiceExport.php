<?php

namespace App\Exports;

use App\Service;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class ServiceExport implements FromQuery
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;

    public $service;

    public function __construct(int $service)
    {
        $this->service = $service;
    }

    public function query()
    {
        return Service::query()->where('id', $this->service);
    }
}
