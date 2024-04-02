<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        DB::disableQueryLog(); 

        // Llama a otros seeders aquí
        $this->call([
            // Agrega los nombres de tus otros seeders específicos a ejecutar
        ]);

    
    }
}
