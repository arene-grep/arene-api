<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mehradsadeghi\FilterQueryString\FilterQueryString;

/**
 * @mixin IdeHelperProduct
 */
class Product extends Model
{
    use HasFactory, SoftDeletes;
    use FilterQueryString;

    protected $fillable = [
        'name',
        'price',
        'stock',
        'minimum_stock',
        'category_id',
        'trading_card_game_id',
        'language_id',
    ];

    protected $casts = [
        'price' => 'double',
        'stock' => 'integer',
        'minimum_stock' => 'integer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $filters = [
        'name',
        'price',
        'category_id',
        'trading_card_game_id',
        'language_id',
        'greater',
        'greater_or_equal',
        'less',
        'less_or_equal',
        'between',
        'not_between'
    ];

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function trading_card_game()
    {
        return $this->belongsTo('App\Models\TradingCardGame');
    }

    public function language()
    {
        return $this->belongsTo('App\Models\Language');
    }
}
