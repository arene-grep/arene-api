<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'price',
        'stock',
        'minimum_stock',
        'category_id',
        'trading_card_game_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function users()
    {
        return $this->belongsToMany('App\Models\User');
    }

    public function trading_card_game()
    {
        return $this->belongsTo('App\Models\TradingCardGame');
    }
}
