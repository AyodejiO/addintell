<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Recipe;
use Illuminate\Database\Seeder;
use App\Models\RecipeIngredient;

class RecipeIngredientTableSeeder extends Seeder
{
    public function run()
    {
        // create ingredients
        $tomato = Ingredient::updateOrCreate(['name' => 'Tomato', 'price' => 1.0]);
        $mozzarella = Ingredient::updateOrCreate(['name' => 'Mozzarella', 'price' => 0.5]);
        $ham = Ingredient::updateOrCreate(['name' => 'Ham', 'price' => 1.5]);
        $pineapple = Ingredient::updateOrCreate(['name' => 'Pineapple', 'price' => 1.0]);

        $this->command->info('Ingredient table seeded!');

        // create recipes
        $margherita = Recipe::updateOrCreate(['name' => 'Margherita', 'price' => 6.99]);
        $hawaiian = Recipe::updateOrCreate(['name' => 'Hawaiian', 'price' => 8.99]);

        $this->command->info('Recipe table seeded!');

        // create recipe ingredients
        RecipeIngredient::updateOrCreate([
            'recipe_id' => $margherita->id, 
            'ingredient_id' => $tomato->id,
            'amount' => 2
        ]);

        RecipeIngredient::updateOrCreate([
            'recipe_id' => $margherita->id, 
            'ingredient_id' => $mozzarella->id,
            'amount' => 2
        ]);

        RecipeIngredient::updateOrCreate([
            'recipe_id' => $hawaiian->id, 
            'ingredient_id' => $tomato->id,
            'amount' => 2
        ]);

        RecipeIngredient::updateOrCreate([
            'recipe_id' => $hawaiian->id, 
            'ingredient_id' => $mozzarella->id,
            'amount' => 2
        ]);

        RecipeIngredient::updateOrCreate([
            'recipe_id' => $hawaiian->id, 
            'ingredient_id' => $ham->id,
            'amount' => 1
        ]);

        RecipeIngredient::updateOrCreate([
            'recipe_id' => $hawaiian->id, 
            'ingredient_id' => $pineapple->id,
            'amount' => 1
        ]);
    }
}
