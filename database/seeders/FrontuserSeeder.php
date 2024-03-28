<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            ->each(function ($chunk) {
                $records = $chunk->map(function ($row) {
                    return [
                        'Nombre' => $row[0],
                        'Descripcion' => $row[1],
                        'Proveedor' => $row[2],
                        'stock' => $row[3],
                        'Precio_venta' => $row[4],
                        'Precio_compra' => $row[5],
                        'Fecha' => $row[6],
                    ];
                })->toArray();

                DB::table('products')->insert($records);
            });
    }
}
