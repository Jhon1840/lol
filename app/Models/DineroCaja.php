<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DineroCaja extends Model
{
    use HasFactory;

    protected $table = 'dinero_caja';

    protected $fillable = [
        'caja_id',
        'tipo',
        'denominacion',
        'cantidad'
    ];

    public function caja()
    {
        return $this->belongsTo(Caja::class);
    }
}
