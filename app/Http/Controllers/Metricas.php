<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Caja;
class Metricas extends Controller
{
    public function index()
{
    $products = Product::paginate(10);
    $productosMasVendidos = Product::join('venta_detalles', 'venta_detalles.producto_id', '=', 'products.id')
        ->select('products.Nombre', DB::raw('SUM(venta_detalles.cantidad) as total_vendido'))
        ->groupBy('venta_detalles.producto_id')
        ->orderBy('total_vendido', 'desc')
        ->get();
    $maxVendidos = $productosMasVendidos->max('total_vendido');

    // Calcular el número total de ventas y el total recaudado
    $totalVentas = Venta::count();
    $totalRecaudado = Venta::sum('total');

    // Obtener los productos que más dinero han generado
    $productosMasRentables = Product::join('venta_detalles', 'venta_detalles.producto_id', '=', 'products.id')
        ->select('products.Nombre', DB::raw('SUM(venta_detalles.subtotal) as total_generado'))
        ->groupBy('venta_detalles.producto_id')
        ->orderBy('total_generado', 'desc')
        ->get();

    $tasks = Caja::all();

    return view('metricas.metricas', compact('products', 'productosMasVendidos', 'maxVendidos', 'totalVentas', 'totalRecaudado', 'productosMasRentables','tasks'))
        ->with('i', (request()->input('page', 1) - 1) * $products->perPage());

    
}

    

}
