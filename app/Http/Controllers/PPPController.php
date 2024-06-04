<?php

// app/Http/Controllers/PPPController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PPPController extends Controller
{
    public function calculatePPP()
    {
        $productos = DB::table('products')
            ->select('id', 'Nombre', 'stock', 'Precio_compra')
            ->get();

        $totalCosto = 0;
        $totalProductos = 0;

        // Calcular el costo total y el total de productos
        foreach ($productos as $producto) {
            $totalCosto += $producto->Precio_compra;
            $totalProductos += $producto->stock;
        }

        if ($totalProductos > 0) {
            $ppp = $totalCosto / $totalProductos;
        } else {
            $ppp = 0; // Evitar división por cero
        }

        return response()->json(['ppp' => $ppp]);
    }
}
