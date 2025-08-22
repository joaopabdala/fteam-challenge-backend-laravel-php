<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'external_id' => $this->faker->unique()->randomNumber(5),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph,
            'price' => $this->faker->numberBetween(1000, 20000),
            'image' => $this->faker->imageUrl(),
            'rating_rate' => $this->faker->randomFloat(2, 1, 5),
            'rating_count' => $this->faker->numberBetween(0, 500),
            'category_id' => Category::factory(),
        ];
    }
}
