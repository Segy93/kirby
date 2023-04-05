<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersLocalTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('UsersLocal', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->unique('userslocal_id_unique');
			$table->string('username', 63)->unique('userslocal_username_unique');
			$table->string('email', 127)->unique('userslocal_email_unique');
			$table->string('profile_picture', 127)->nullable();
			$table->string('password', 127);
			$table->string('activation_token', 127)->nullable();
			$table->dateTime('activation_token_expired')->nullable();
			$table->string('password_reset_token', 127)->nullable();
			$table->dateTime('password_reset_expired')->nullable();
            $table->tinyInteger('cookies_accepted')->unsigned()->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('UsersLocal');
	}

}
