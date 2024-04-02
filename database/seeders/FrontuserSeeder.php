<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Facades\DB;

class FrontuserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::disableQueryLog();

        $filePath = public_path('productos.csv');

        $lastId = DB::table('products')->max('id') ?? 0;

        LazyCollection::make(function () use ($filePath) {
            $handle = fopen($filePath, 'r');

            while (($line = fgetcsv($handle, 4096)) !== false) {
                $dataString = implode(',', $line);
                $row = explode(',', $dataString);
                yield $row;
            }

            fclose($handle);
        })
            ->skip(1) 
            ->chunk(1000) 
            ->each(function ($chunk) use (&$lastId) {
                foreach ($chunk as $row) {
                    $existingProduct = DB::table('products')
                        ->where('Nombre', $row[0])
                        ->where('Proveedor', $row[2])
                        ->first();

                    if ($existingProduct) {
                        // Update stock
                        DB::table('products')
                            ->where('id', $existingProduct->id)
                            ->update(['stock' => $existingProduct->stock + $row[3]]);
                    } else {
                        $lastId++;
                        // Insert new record
                        DB::table('products')->insert([
                            'id' => $lastId,
                            'Nombre' => $row[0],
                            'Descripcion' => $row[1],
                            'Proveedor' => $row[2],
                            'stock' => $row[3],
                            'Precio_venta' => $row[4],
                            'Precio_compra' => $row[5],
                            'Fecha' => $row[6],
                        ]);
                    }
                }
            });
    }
}
