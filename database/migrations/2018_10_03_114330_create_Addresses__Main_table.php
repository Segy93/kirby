<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAddressesMainTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('Addresses__Main', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('city', 127);
			$table->string('address', 127);
			$table->char('postal_code', 5);
			$table->string('discr', 15);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('Addresses__Main');
	}

}
