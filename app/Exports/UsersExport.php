<?php

/*
namespace App\Exports; // Asegúrate de usar este espacio de nombres

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection; // Importa la clase

class UsersExport implements FromCollection
{
    public function collection()
    {
        return User::all(); // Selecciona todos los usuarios
    }
}
