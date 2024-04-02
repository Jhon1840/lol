<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Database\Seeders\FrontuserSeeder;
use Illuminate\Routing\Controller;

class UploadController extends Controller
{
    public function uploadCsv(Request $request)
    {
        $csvFile = $request->file('csvFile');

        if ($csvFile) {
            $csvPath = $csvFile->store('temp'); // Almacenar el archivo temporalmente
            
            $seeder = new FrontuserSeeder();
            $seeder->run($csvPath); // Ejecutar el seeder pasando la ruta del archivo .csv

            return response()->json(['message' => 'Archivo CSV procesado exitosamente']);
        }

        return response()->json(['error' => 'No se pudo procesar el archivo CSV'], 422);
    }
}