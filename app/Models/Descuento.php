<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Descuento
 *
 * @property $id
 * @property $product_id
 * @property $discount_percentage
 * @property $start_date
 * @property $end_date
 * @property $created_at
 * @property $updated_at
 *
 * @property Product $product
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Descuento extends Model
{
    
    static $rules = [
		'product_id' => 'required',
		'discount_percentage' => 'required',
		'start_date' => 'required',
		'end_date' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['product_id','discount_percentage','start_date','end_date'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function product()
    {
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }
    

}
