<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToBannersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('Banners', function(Blueprint $table)
		{
			$table->foreign('position_id')->references('id')->on('Positions')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('Banners', function(Blueprint $table)
		{
			$table->dropForeign('banners_position_id_foreign');
		});
	}

}
