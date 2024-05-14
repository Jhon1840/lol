<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Caja;

class CajaController extends Controller
{
    public function toggleCaja(Request $request)
    {
        $caja = Caja::find($request->id);

        if ($caja->estado == 0) {
            $caja->estado = 1;
            $caja->fecha = now();
            // Lógica adicional para abrir la caja
        } else {
            $caja->estado = 0;
            // Lógica adicional para cerrar la caja
        }

        $caja->save();

        return back();
    }
}
