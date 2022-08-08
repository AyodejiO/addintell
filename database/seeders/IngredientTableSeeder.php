<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use App\Models\Ingredient;

class IngredientTableSeeder extends Seeder
{

    public function run()
    {
        Ingredient::factory()
                ->count(3)
                ->state(new Sequence(
                    ['name' => 'Tomato'],
                    ['name' => 'Mozzarella'],
                    ['name' => 'Ham'],
                    ['name' => 'Pineapple']
                ))
                ->create();

        Ingredient::updateOrCreate(
            // ['id' => Ingredient::TOMATO_ID],
            ['name' => 'Tomato']
        );

        Ingredient::updateOrCreate(
            // ['id' => Ingredient::MOZZARELLA_ID],
            ['name' => 'Mozzarella']
        );

        Ingredient::updateOrCreate(
            // ['id' => Ingredient::HAM_ID],
            ['name' => 'Ham']
        );

        Ingredient::updateOrCreate(
            // ['id' => Ingredient::PINEAPPLE_ID],
            ['name' => 'Pineapple']
        );
    }
}
