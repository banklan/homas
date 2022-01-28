<?php

namespace App\Http\Controllers;

use App\Service;
use App\Exports\UsersExport;
use Illuminate\Http\Request;
use App\Exports\ServiceExport;
use App\Exports\ServicesExport;
use App\Exports\CategorysExport;
use App\Exports\LocationsExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportLocations()
    {
        ob_end_clean();
        return Excel::download(new LocationsExport, 'locations.xlsx');
    }

    public function exportCategories()
    {
        ob_end_clean();
        return Excel::download(new CategorysExport, 'categories.xlsx');
    }

    public function exportServices()
    {
        ob_end_clean();
        return Excel::download(new ServicesExport, 'services.xlsx');
    }

    public function exportService($id)
    {
        ob_end_clean();
        return Excel::download(new ServiceExport($id), 'service.xlsx');
    }

    public function exportUsers()
    {
        ob_end_clean();
        return Excel::download(new UsersExport, 'users.xlsx');
    }
}
