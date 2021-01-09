<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\TradingCardGame;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::factory()
            ->count(50)
            ->state(self::getCategorySequence())
            ->state(self::getTradingCardSequence())
            ->create();
    }

    private function getTradingCardSequence(): Sequence
    {
        $trading_card_games = TradingCardGame::all();
        $sequence = [];
        foreach ($trading_card_games as $trading_card_game)
            //just to let half of products with a null trading_card_game_id
            array_push($sequence, ['trading_card_game_id' => ($trading_card_game->id % 2) ? $trading_card_game->id : null]);
        return new Sequence(...$sequence);
    }

    private function getCategorySequence(): Sequence
    {
        $categories = Category::all();
        $sequence = [];
        foreach ($categories as $category)
            array_push($sequence, ['category_id' => $category->id]);
        return new Sequence(...$sequence);
    }
}
