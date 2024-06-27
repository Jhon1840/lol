<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Illuminate\Http\Request;

class Facturas extends Controller
{
    public function index()
{
    $ventas = Venta::whereNotNull('factura_url')->get();
    
    $facturas = $ventas->map(function ($venta) {
        return [
            'id' => $venta->id,
            'fecha' => $venta->fecha,
            'cliente' => $venta->cliente,
            'total' => $venta->total,
            'url' => asset($venta->factura_url), // Usa asset() para generar la URL completa
            'nombre_archivo' => basename($venta->factura_url)
        ];
    });

    return view('facturas.index', compact('facturas'));
}

}