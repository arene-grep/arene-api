<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\Buy;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class BuySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Buy::factory()
            ->count(5)
            ->state(self::getOrderSequence())
            ->state(self::getProductSequence())
            ->create();
    }
    private function getOrderSequence(): Sequence
    {
        $orders = Order::all();
        $sequence = [];
        foreach ($orders as $order)
            array_push($sequence, ['order_id' => $order->id]);
        return new Sequence(...$sequence);
    }
    private function getProductSequence(): Sequence
    {
        $products = Product::all();
        $sequence = [];
        foreach ($products as $product)
            array_push($sequence, ['product_id' => $product->id]);
        return new Sequence(...$sequence);
    }
}
