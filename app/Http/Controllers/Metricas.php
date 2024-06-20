<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Caja;
use Carbon\Carbon;

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

        // Obtener los productos con menos de 50 unidades en stock
        $productosMenorStock = Product::where('stock', '<', 50)
            ->orderBy('stock', 'asc')
            ->get();

        // Obtener las ventas canceladas y calcular el total del dinero devuelto
        $ventasCanceladas = Venta::where('estado', 'cancelado')->get();
        $totalDineroDevuelto = $ventasCanceladas->sum('total');

        $tasks = Caja::all();

        return view('metricas.metricas', compact('products', 'productosMasVendidos', 'maxVendidos', 'totalVentas', 'totalRecaudado', 'productosMasRentables', 'productosMenorStock', 'ventasCanceladas', 'totalDineroDevuelto', 'tasks'))
            ->with('i', (request()->input('page', 1) - 1) * $products->perPage());
    }

    public function getMostSoldProducts($period)
    {
        switch ($period) {
            case 'day':
                $productosMasVendidos = DB::table('venta_detalles')
                    ->join('products', 'venta_detalles.producto_id', '=', 'products.id')
                    ->select('products.Nombre', DB::raw('SUM(venta_detalles.cantidad) as total_vendido'))
                    ->whereDate('venta_detalles.created_at', '=', Carbon::today())
                    ->groupBy('products.Nombre')
                    ->orderByDesc('total_vendido')
                    ->get();
                break;
            case 'week':
                $productosMasVendidos = DB::table('venta_detalles')
                    ->join('products', 'venta_detalles.producto_id', '=', 'products.id')
                    ->select('products.Nombre', DB::raw('SUM(venta_detalles.cantidad) as total_vendido'))
                    ->whereBetween('venta_detalles.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->groupBy('products.Nombre')
                    ->orderByDesc('total_vendido')
                    ->get();
                break;
            case 'month':
                $productosMasVendidos = DB::table('venta_detalles')
                    ->join('products', 'venta_detalles.producto_id', '=', 'products.id')
                    ->select('products.Nombre', DB::raw('SUM(venta_detalles.cantidad) as total_vendido'))
                    ->whereMonth('venta_detalles.created_at', '=', Carbon::now()->month)
                    ->groupBy('products.Nombre')
                    ->orderByDesc('total_vendido')
                    ->get();
                break;
            default:
                return response()->json(['error' => 'Invalid period'], 400);
        }

        $labels = $productosMasVendidos->pluck('Nombre');
        $data = $productosMasVendidos->pluck('total_vendido');

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }
}
