<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::inRandomOrder()->value('id') ?? Category::factory(),
            'name' => fake()->name(),
            'description' => fake()->text(),
            'price' => fake()->randomFloat(2, 10, 1000),
            'stock_quantity' => fake()->randomNumber(2, 100),
            'image_url' => fake()->imageUrl(640, 480),
        ];
    }
}
