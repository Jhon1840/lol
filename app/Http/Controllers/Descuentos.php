<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
namespace App\Http\Controllers;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan; // Agregar esta línea
use App\Models\Product;
use Illuminate\Support\Facades\DB;
class Descuentos extends Controller
{
    public function index()
    {
        // Paginación de productos
        $products = Product::paginate(10);

        // Consulta para obtener los productos más vendidos
        $productosMasVendidos = Product::join('venta_detalles', 'venta_detalles.producto_id', '=', 'products.id')
            ->select('products.Nombre', DB::raw('SUM(venta_detalles.cantidad) as total_vendido'))
            ->groupBy('venta_detalles.producto_id')
            ->orderBy('total_vendido', 'desc')
            ->get();

        // Retorna a la vista con ambas colecciones de datos
        return view('descuentos.descuentos', compact('products', 'productosMasVendidos'))
            ->with('i', (request()->input('page', 1) - 1) * $products->perPage());
    }
}
