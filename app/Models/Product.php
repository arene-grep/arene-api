<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mehradsadeghi\FilterQueryString\FilterQueryString;

/**
 * @mixin IdeHelperProduct
 * @method static filter()
 */
class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    use FilterQueryString;

    protected $filters = ['name', 'price', 'category_id', 'trading_card_game_id', 'language_id'];

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
