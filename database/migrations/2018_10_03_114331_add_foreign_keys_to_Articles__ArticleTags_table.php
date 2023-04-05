<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToArticlesArticleTagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('Articles__ArticleTags', function(Blueprint $table)
		{
			$table->foreign('article_id')->references('id')->on('Articles__Main')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('tag_id')->references('id')->on('Articles__Tags')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('Articles__ArticleTags', function(Blueprint $table)
		{
			$table->dropForeign('articles__articletags_article_id_foreign');
			$table->dropForeign('articles__articletags_tag_id_foreign');
		});
	}

}
