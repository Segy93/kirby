<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToArticlesMainTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('Articles__Main', function(Blueprint $table)
		{
			$table->foreign('author_id')->references('id')->on('Admins')->onUpdate('RESTRICT')->onDelete('SET NULL');
			$table->foreign('category_id')->references('id')->on('Articles__Categories')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('Articles__Main', function(Blueprint $table)
		{
			$table->dropForeign('articles__main_author_id_foreign');
			$table->dropForeign('articles__main_category_id_foreign');
		});
	}

}
