<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderRecipesTable extends Migration {

	public function up()
	{
		Schema::create('luigis_order_recipes', function(Blueprint $table) {
			$table->id();
			$table->integer('order_id')->unsigned();
			$table->integer('recipe_id')->unsigned();
			$table->float('total')->default(0);
		});
	}

	public function down()
	{
		Schema::drop('luigis_order_recipes');
	}
}
