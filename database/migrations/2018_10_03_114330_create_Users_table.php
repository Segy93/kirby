<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('Users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 63)->nullable();
			$table->string('surname', 63)->nullable();
			$table->timestamp('registration_date')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->dateTime('last_visited')->nullable();
			$table->dateTime('banned')->nullable();
			$table->string('phone_nr', 31)->nullable();
			$table->boolean('status')->default(1);
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
		Schema::drop('Users');
	}

}
