<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdminsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('Admins', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('role_id')->unsigned()->index('admins_role_id_foreign');
			$table->string('username', 127)->unique('admins_username_unique');
			$table->string('email', 127)->unique('admins_email_unique');
			$table->string('password', 127);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('Admins');
	}

}
