<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderIngredientsTable extends Migration {

	public function up()
	{
		Schema::create('luigis_order_ingredients', function(Blueprint $table) {
			$table->id();
			$table->integer('order_id')->unsigned();
			$table->integer('recipe_id')->unsigned();
			$table->integer('ingredient_id')->unsigned();
			$table->integer('amount')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('luigis_order_ingredients');
	}
}
