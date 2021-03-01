<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperBuy
 */
class Buy extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'quantity',
        'order_id',
        'product_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }

    public function product(){
        return $this->belongsTo('App\Models\Product');
    }
}
