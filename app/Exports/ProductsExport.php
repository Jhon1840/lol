<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Product::select('Nombre', 'Descripcion', 'Proveedor', 'stock', 'Precio_venta', 'Precio_compra', 'Fecha')->get();
    }

    public function headings(): array
    {
        return [
            'Nombre',
            'Descripcion',
            'Proveedor',
            'stock',
            'Precio_venta',
            'Precio_compra',
            'Fecha',
        ];
    }
}