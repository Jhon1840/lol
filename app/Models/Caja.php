<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    use HasFactory;

    protected $table = 'caja';

    protected $fillable = [
        'nombre_vendedor',
        'dinero',
        'fecha',
        'id_vendedor',
        'estado',
        'observaciones',
        'total_billetes_monedas'
    ];

    protected $hidden = [];
}
