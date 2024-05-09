<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 *
 * @property $id
 * @property $Nombre
 * @property $Descripcion
 * @property $Proveedor
 * @property $stock
 * @property $Precio_venta
 * @property $Precio_compra
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Product extends Model
{
    
    static $rules = [
		'Nombre' => 'required',
		'Descripcion' => 'required',
		'Proveedor' => 'required',
		'stock' => 'required',
		'Precio_venta' => 'required',
		'Precio_compra' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['Nombre','Descripcion','Proveedor','stock','Precio_venta','Precio_compra'];

    

}
