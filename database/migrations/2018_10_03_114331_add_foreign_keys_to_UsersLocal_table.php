<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToUsersLocalTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('UsersLocal', function(Blueprint $table)
		{
			$table->foreign('id')->references('id')->on('Users')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('UsersLocal', function(Blueprint $table)
		{
			$table->dropForeign('userslocal_id_foreign');
		});
	}

}
