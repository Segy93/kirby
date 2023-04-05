<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCommentsArticleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('Comments__Article', function(Blueprint $table)
		{
			$table->foreign('article_id')->references('id')->on('Articles__Main')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('id')->references('id')->on('Comments__Main')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('Comments__Article', function(Blueprint $table)
		{
			$table->dropForeign('comments__article_article_id_foreign');
			$table->dropForeign('comments__article_id_foreign');
		});
	}

}
