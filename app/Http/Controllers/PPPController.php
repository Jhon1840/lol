<?php

// app/Http/Controllers/PPPController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PPPController extends Controller
{
    public function calculatePPP()
    {
        // Obtener todos los productos de la base de datos
        $productos = DB::table('products')
            ->select('id', 'Nombre', 'stock', 'Precio_compra')
            ->get();

        // Inicializar variables para calcular el PPP
        $totalCosto = 0;
        $totalProductos = 0;

        // Calcular el costo total y el total de productos
        foreach ($productos as $producto) {
            $totalCosto += $producto->Precio_compra;
            $totalProductos += $producto->stock;
        }

        // Calcular el PPP
        if ($totalProductos > 0) {
            $ppp = $totalCosto / $totalProductos;
        } else {
            $ppp = 0; // Evitar división por cero
        }

        // Retornar el PPP en formato JSON
        return response()->json(['ppp' => $ppp]);
    }
}
