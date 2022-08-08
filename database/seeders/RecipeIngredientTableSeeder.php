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
        $tomato = Ingredient::updateOrCreate(['name' => 'Tomato']);
        $mozzarella = Ingredient::updateOrCreate(['name' => 'Mozzarella']);
        $ham = Ingredient::updateOrCreate(['name' => 'Ham']);

        $this->command->info('Ingredient table seeded!');

        // create recipes
        $pineapple = Ingredient::updateOrCreate(['name' => 'Pineapple']);
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
