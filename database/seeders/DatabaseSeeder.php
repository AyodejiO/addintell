<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	public function run()
	{
		Model::unguard();

		$this->call(RecipeIngredientTableSeeder::class);
		$this->command->info('RecipeIngredient table seeded!');
	}
}
