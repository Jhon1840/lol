<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Crear el rol 'admin' si no existe
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web'
        ]);

        // Crear el rol 'vendedor' si no existe
        $vendedorRole = Role::firstOrCreate([
            'name' => 'vendedor',
            'guard_name' => 'web'
        ]);

        // Crear el usuario admin si no existe
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin123')
            ]
        );

        // Asignar el rol de admin al usuario admin
        if (!$adminUser->hasRole('admin')) {
            $adminUser->assignRole($adminRole);
        }

        // Crear el usuario vendedor si no existe
        $vendedorUser = User::firstOrCreate(
            ['email' => 'vendedor@gmail.com'],
            [
                'name' => 'Vendedor',
                'password' => Hash::make('vendedor123')
            ]
        );

        // Asignar el rol de vendedor al usuario vendedor
        if (!$vendedorUser->hasRole('vendedor')) {
            $vendedorUser->assignRole($vendedorRole);
        }
    }
}
