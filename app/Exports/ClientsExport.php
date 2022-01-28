<?php

namespace App\Exports;

use App\TestClient;
// use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;


class ClientsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // use Exportable;

    // protected $clients;

    // public function __construct(TestClient $clients){
    //     $this->clients = $clients;
    // }

    public function collection()
    {
        return TestClient::all();
    }
}
