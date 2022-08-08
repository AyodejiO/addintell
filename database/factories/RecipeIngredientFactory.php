<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class RecipeIngredientFactory extends Factory
{
    protected $model = \App\Models\RecipeIngredient::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'recipe_id' => $this->faker->numberBetween(1, 10),
            'ingredient_id' => $this->faker->numberBetween(1, 10),
            'amount' => $this->faker->randomFloat(1, 10),
        ];
    }
}
