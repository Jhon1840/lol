<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Crear roles si no existen
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $vendedorRole = Role::firstOrCreate(['name' => 'vendedor', 'guard_name' => 'web']);

        // Crear permisos si no existen
        $adminPermission = Permission::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $cajeroPermission = Permission::firstOrCreate(['name' => 'cajero', 'guard_name' => 'web']);
        $writterrPermission = Permission::firstOrCreate(['name' => 'writterr', 'guard_name' => 'web']);

        // Crear usuario admin si no existe
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin123')
            ]
        );

        // Asignar rol y permisos al usuario admin
        if (!$adminUser->hasRole('admin')) {
            $adminUser->assignRole($adminRole);
        }

        $adminUser->givePermissionTo($adminPermission);
        $adminUser->givePermissionTo($writterrPermission);

        // Crear usuario vendedor si no existe
        $vendedorUser = User::firstOrCreate(
            ['email' => 'vendedor@gmail.com'],
            [
                'name' => 'Vendedor',
                'password' => Hash::make('vendedor123')
            ]
        );

        // Asignar rol al usuario vendedor
        if (!$vendedorUser->hasRole('vendedor')) {
            $vendedorUser->assignRole($vendedorRole);
        }

        $vendedorUser->givePermissionTo($cajeroPermission);
        $vendedorUser->givePermissionTo($writterrPermission);

        // Crear dos usuarios adicionales con el permiso writterr
        $user1 = User::firstOrCreate(
            ['email' => 'matias@gmail.com'],
            [
                'name' => 'Matias',
                'password' => Hash::make('password123')
            ]
        );

        $user2 = User::firstOrCreate(
            ['email' => 'user2@gmail.com'],
            [
                'name' => 'Usuario Dos',
                'password' => Hash::make('password123')
            ]
        );

        // Asignar el permiso writterr a los nuevos usuarios
        $user1->givePermissionTo($writterrPermission);
        $user2->givePermissionTo($writterrPermission);
    }
}
