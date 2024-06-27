<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use Illuminate\Http\Request;

class CajaController extends Controller
{
    public function index()
    {
        // Obtén todas las URLs de los archivos PDF desde la base de datos
        $cajas = Caja::whereNotNull('url')->get();

        // Devuelve la vista con las URLs de los archivos PDF
        return view('caja.index', compact('cajas'));
    }
}
