<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\DineroCaja;
use Illuminate\Http\Request;

class CajaController extends Controller
{
    public function cerrarCaja(Request $request)
    {
        $request->validate([
            'caja_id' => 'required|integer|exists:caja,id',
            'dinero_en_caja' => 'required|numeric',
            'total_billetes_monedas' => 'required|numeric',
            'observaciones' => 'nullable|string'
        ]);

        try {
            $caja = Caja::findOrFail($request->caja_id);
            $caja->estado = 'caja cerrada';
            $caja->dinero_final = $request->total_billetes_monedas;
            $caja->observaciones = $request->observaciones;
            $caja->save();

            // Guardar billetes y monedas en la tabla dinero_caja
            $billetes = $request->input('billetes', []);
            $monedas = $request->input('monedas', []);

            foreach ($billetes as $denominacion => $cantidad) {
                if ($cantidad > 0) {
                    DineroCaja::create([
                        'caja_id' => $caja->id,
                        'tipo' => 'billete',
                        'denominacion' => $denominacion,
                        'cantidad' => $cantidad,
                    ]);
                }
            }

            foreach ($monedas as $denominacion => $cantidad) {
                if ($cantidad > 0) {
                    DineroCaja::create([
                        'caja_id' => $caja->id,
                        'tipo' => 'moneda',
                        'denominacion' => $denominacion,
                        'cantidad' => $cantidad,
                    ]);
                }
            }

            return response()->json(['success' => true, 'redirect' => route('ventas.index')]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al cerrar la caja: ' . $e->getMessage()]);
        }
    }
}
