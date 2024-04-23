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
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Coloca tu función aquí
        function custom_array_flatten($array) {
            $result = [];
            foreach ($array as $item) {
                if (is_array($item)) {
                    $result = array_merge($result, custom_array_flatten($item));
                } else {
                    $result[] = $item;
                }
            }
            return $result;
        }
    }
}
