<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToCollection, WithBatchInserts, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $existingProduct = Product::where('Nombre', $row['nombre'])
                ->where('Proveedor', $row['proveedor'])
                ->first();

            if ($existingProduct) {
                $existingProduct->stock += $row['stock'];
                $existingProduct->save();
            } else {
                Product::create([
                    'Nombre' => $row['nombre'],
                    'Descripcion' => $row['descripcion'],
                    'Proveedor' => $row['proveedor'],
                    'stock' => $row['stock'],
                    'Precio_venta' => $row['precio_venta'],
                    'Precio_compra' => $row['precio_compra'],
                    'Fecha' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['fecha']),
                ]);
            }
        }
    }

    public function batchSize(): int
    {
        return 1000;
    }
}