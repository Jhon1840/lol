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
                $stockAnterior = $existingProduct->stock;
                $precioCompraAnterior = $existingProduct->Precio_compra;
                $nuevoStock = $stockAnterior + $row['stock'];
                
                $nuevoCostoTotal = ($stockAnterior * $precioCompraAnterior) + ($row['stock'] * $row['precio_compra']);
                $nuevoPPP = $nuevoCostoTotal / $nuevoStock;

                $existingProduct->stock = $nuevoStock;
                $existingProduct->Precio_compra = $nuevoPPP;
                $existingProduct->Precio_venta = $nuevoPPP * 1.20; 
                $existingProduct->save();
            } else {
                $ppp = $row['precio_compra']; 
                $precioVenta = $ppp * 1.20; 

                Product::create([
                    'Nombre' => $row['nombre'],
                    'Descripcion' => $row['descripcion'],
                    'Proveedor' => $row['proveedor'],
                    'stock' => $row['stock'],
                    'Precio_compra' => $ppp,
                    'Precio_venta' => $precioVenta,
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
