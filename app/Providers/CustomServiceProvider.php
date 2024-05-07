<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CustomServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Aquí puedes registrar cualquier dependencia o servicio con el contenedor de servicios
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Puedes llamar a tu método personalizado aquí si es necesario en el arranque
        $exampleArray = [[1, 2], [3, [4, 5]]];
        $flattened = $this->customArrayFlatten($exampleArray);
        // Puedes hacer algo con $flattened si es necesario
    }

    /**
     * Aplana un array de manera recursiva.
     * 
     * @param array $array El array a aplanar.
     * @return array El array aplanado.
     */
    private function customArrayFlatten($array)
    {
        $result = [];
        foreach ($array as $item) {
            if (is_array($item)) {
                $result = array_merge($result, $this->customArrayFlatten($item));
            } else {
                $result[] = $item;
            }
        }
        return $result;
    }
}
