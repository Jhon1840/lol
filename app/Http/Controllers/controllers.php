<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;

class CsvController extends Controller
{
    public function upload(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048',
        ]);

        // Almacenar el archivo
        $file = $request->file('file');
        $path = Storage::putFile('uploads', $file);

        // Guardar el archivo con el nombre "productos.csv"
        Storage::move($path, 'productos.csv');

        // Ejecutar el seeder
        Artisan::call('db:seed', ['--class' => 'FrontuserSeeder']);

        // Redirigir al usuario a una página de éxito
        return redirect()->route('home')->with('success', 'Archivo CSV subido y procesado correctamente.');
    }
}
