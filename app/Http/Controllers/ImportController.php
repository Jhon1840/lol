<?php

namespace App\Http\Controllers;

use App\Imports\ProductsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function importProducts(Request $request)
    {
        
        $file = $request->file('file_svc');

        try {
            Excel::import(new ProductsImport, $file);
            return redirect()->back()->with('success', 'Datos importados correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al importar los datos: ' . $e->getMessage());
        }
    }
}
