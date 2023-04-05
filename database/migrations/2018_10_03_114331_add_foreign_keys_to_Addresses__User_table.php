<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToAddressesUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('Addresses__User', function(Blueprint $table)
		{
			$table->foreign('id')->references('id')->on('Addresses__Main')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('user_id')->references('id')->on('Users')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('Addresses__User', function(Blueprint $table)
		{
			$table->dropForeign('addresses__user_id_foreign');
			$table->dropForeign('addresses__user_user_id_foreign');
		});
	}

}
