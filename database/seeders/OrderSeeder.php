<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Order::factory()
            ->count(5)
            ->state(self::getUserSequence())
            ->create();
    }

    private function getUserSequence(): Sequence
    {
        $users = User::all();
        $sequence = [];
        foreach ($users as $user)
            array_push($sequence, ['user_id' => $user->id]);
        return new Sequence(...$sequence);
    }
}
