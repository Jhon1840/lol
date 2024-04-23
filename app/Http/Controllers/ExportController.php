<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsExport;

class ExportController extends Controller
{
    public function exportProducts()
    {
        return Excel::download(new ProductsExport, 'productos.csv');
    }
}
