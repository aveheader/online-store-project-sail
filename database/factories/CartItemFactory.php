<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CartItemFactory extends Factory
{
    protected $model = CartItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cart_id' => Cart::inRandomOrder()->value('id') ?? Cart::factory(),
            'product_id' => Product::inRandomOrder()->value('id') ?? Product::factory(),
            'quantity' => fake()->randomNumber(1, 10),
        ];
    }
}
