<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPositionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('Positions', function(Blueprint $table)
		{
			$table->foreign('page_type_id')->references('id')->on('PageTypes')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('Positions', function(Blueprint $table)
		{
			$table->dropForeign('positions_page_type_id_foreign');
		});
	}

}
