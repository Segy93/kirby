<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToStaticPagesMainTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('StaticPages__Main', function(Blueprint $table)
		{
			$table->foreign('category_id')->references('id')->on('StaticPages__Categories')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('StaticPages__Main', function(Blueprint $table)
		{
			$table->dropForeign('staticpages__main_category_id_foreign');
		});
	}

}
