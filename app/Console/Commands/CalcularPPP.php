<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CalcularPPP extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calcular:ppp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calcula el Precio Promedio Ponderado (PPP) de los productos';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $productos = DB::table('products')
            ->select('id', 'Nombre', 'stock', 'Precio_compra')
            ->get();

        if ($productos->isEmpty()) {
            $this->error('No hay datos en la tabla de productos.');
            return 0;
        }

        $this->info('Calculando el Precio Promedio Ponderado (PPP) para cada producto:');

        foreach ($productos as $producto) {
            $pppProducto = $producto->Precio_compra / $producto->stock;
            $this->info("Producto: $producto->Nombre | PPP: $pppProducto");
        }

        return 0;
    }
}
