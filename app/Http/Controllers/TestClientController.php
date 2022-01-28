<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Maatwebsite\Excel\Excel;
use App\Exports\ClientsExport;
use App\Imports\ClientsImport;
use Maatwebsite\Excel\Facades\Excel;

class TestClientController extends Controller
{
    public function export()
    {
        // return Excel::download(new ClientsExport, 'clients.pdf');
        ob_end_clean();
        return Excel::download(new ClientsExport, 'clients.xlsx');
        // return (new ClientsExport)->download('clients.html', \Maatwebsite\Excel\Excel::HTML);
        // return (new ClientsExport)->download('clients.xls', \Maatwebsite\Excel\Excel::XLSX);
        // return (new ClientsExport)->download('clients.xlsx');
        // return (new InvoicesExport)->download('invoices.xlsx');
    }

    public function import(Request $request)
    {
        // $file = $request->file;
        $validator = $this->validate($request, [
            'file' => 'required|file|mimes:csv,txt',
        ]);

        // if($validator->fails()){
        //     return response()->json($validator->messages(), 200);
        // }

        $path = $request->file('file');
        Excel::import(new ClientsImport, $path);
        return response()->json(['message' => 'Uploaded'], 200);
    }
}
