<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Venta
 *
 * @property $id
 * @property $fecha
 * @property $total
 * @property $cliente
 *
 * @property VentaDetalle[] $ventaDetalles
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Venta extends Model
{
    static $rules = [
        'fecha' => 'required',
        'total' => 'required',
    ];

    protected $fillable = ['fecha', 'total', 'cliente'];

    public function ventaDetalles()
    {
        return $this->hasMany('App\Models\VentaDetalle', 'venta_id', 'id');
    }
}
