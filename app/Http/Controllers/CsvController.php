<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan; // Agregar esta línea
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
        $path = Storage::put('public/productos', $file);

        // Renombrar el archivo a 'producto.csv'
        Storage::move($path, 'public/productos/producto.csv');

        // Ejecutar el seeder (asegúrate de reemplazar 'FrontuserSeeder' con el nombre real de tu seeder)
        Artisan::call('db:seed', ['--class' => 'FrontuserSeeder']);

        // Redirigir al usuario a una página de éxito (opcional)
        return redirect()->route('home')->with('success', 'Archivo CSV subido y procesado correctamente.');
    }
}