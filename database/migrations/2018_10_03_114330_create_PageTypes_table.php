<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePageTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('PageTypes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('type', 127)->unique('pagetypes_type_unique');
			$table->string('machine_name', 127);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('PageTypes');
	}

}
