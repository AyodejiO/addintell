<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIngredientsTable extends Migration {

	public function up()
	{
		Schema::create('luigis_ingredients', function(Blueprint $table) {
			$table->id();
			$table->string('name', 255);
			$table->float('price');
		});
	}

	public function down()
	{
		Schema::drop('luigis_ingredients');
	}
}
