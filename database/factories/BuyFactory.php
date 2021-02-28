<?php

namespace Database\Factories;

use App\Models\buy;
use Illuminate\Database\Eloquent\Factories\Factory;

class BuyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = buy::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'quantity' =>$this->faker->randomDigitNotNull,
            'order_id' =>null,
            'product_id' =>null
        ];
    }
}
