<?php

namespace App\Filter;

use Illuminate\Support\Facades\Auth;
use TakiElias\Tablar\Menu\Filters\FilterInterface;
use App\Models\User;

class RolePermissionMenuFilter implements FilterInterface
{
    public function transform($item)
    {
        if (!$this->isVisible($item)) {
            return false;
        }

        return $item['header'] ?? $item;
    }

    protected function isVisible($item)
    {
        $user = Auth::user();

        // Obtener los roles del usuario
        $roles = $user->getRoleNames()->toArray();

        /*// Obtener el correo electrónico del usuario
        $email = $user->email;

        // Imprimir los roles y el correo electrónico por consola
        echo "Roles del usuario: " . implode(', ', $roles) . "\n";
        echo "Correo electrónico del usuario: " . $email . "\n";
*/
        // Check for roles
        $hasAnyRole = array_key_exists('hasAnyRole', $item)
            ? (is_array($item['hasAnyRole']) ? explode(',', implode(',', $item['hasAnyRole'])) : explode(',', $item['hasAnyRole'] ?? ''))
            : null;

        $hasRole = array_key_exists('hasRole', $item)
            ? (is_array($item['hasRole']) ? explode(',', implode(',', $item['hasRole'])) : explode(',', $item['hasRole'] ?? ''))
            : null;

        if (($hasAnyRole && $user->hasAnyRole($hasAnyRole)) || ($hasRole && $user->hasRole($hasRole))) {
            return true;
        }

        return $this->checkPermissions($item, $user) ?? true;
    }

    protected function checkPermissions($item, $user)
    {
        $hasAnyPermission = $item['hasAnyPermission'] ?? null;
        if ($hasAnyPermission) {
            $permissions = $user->getAllPermissions()->pluck('name')->toArray();
           // echo "Permisos del usuario: " . implode(', ', $permissions) . "\n";
            return $user->hasAnyPermission($hasAnyPermission);
        }
        return null;
    }
}
