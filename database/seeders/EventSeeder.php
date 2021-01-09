<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\TradingCardGame;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Event::factory()
            ->count(5)
            ->state(self::getTradingCardSequence())
            ->hasAttached(User::factory()->count(3),
                [
                    'wins' => rand(0,10),
                    'losses' => rand(0,10),
                    'draws' => rand(0,10)
                ]
            )
            ->create();
    }

    private function getTradingCardSequence(): Sequence
    {
        $trading_card_games = TradingCardGame::all();
        $sequence = [];
        foreach ($trading_card_games as $trading_card_game)
            array_push($sequence, ['trading_card_game_id' => $trading_card_game->id]);
        return new Sequence(...$sequence);
    }
}
