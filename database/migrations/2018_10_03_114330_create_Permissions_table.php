<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePermissionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('Permissions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('machine_name', 31)->unique('permissions_machine_name_unique');
			$table->string('description')->unique('permissions_description_unique');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('Permissions');
	}

}
