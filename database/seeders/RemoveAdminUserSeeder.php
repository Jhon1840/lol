<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RemoveAdminUserSeeder extends Seeder
{
    public function run()
    {
        // Buscar y eliminar usuarios específicos
        $adminUser = User::where('email', 'admin@gmail.com')->first();
        $vendedorUser = User::where('email', 'vendedor@gmail.com')->first();
        $user1 = User::where('email', 'mateo@gmail.com')->first();
        $user2 = User::where('email', 'matias@gmail.com')->first();

        // Eliminar los usuarios si existen
        if ($adminUser) {
            $adminUser->delete();
        }

        if ($vendedorUser) {
            $vendedorUser->delete();
        }

        if ($user1) {
            $user1->delete();
        }

        if ($user2) {
            $user2->delete();
        }

        // Buscar y eliminar permisos específicos
        Permission::where('name', 'admin')->delete();
        Permission::where('name', 'cajero')->delete();
        Permission::where('name', 'writterr')->delete();

        // Buscar y eliminar roles específicos
        Role::where('name', 'admin')->delete();
        Role::where('name', 'vendedor')->delete();
    }
}
