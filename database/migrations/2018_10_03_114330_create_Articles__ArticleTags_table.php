<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateArticlesArticleTagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('Articles__ArticleTags', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('article_id')->unsigned();
			$table->integer('tag_id')->unsigned()->index('articles__articletags_tag_id_foreign');
			$table->unique(['article_id','tag_id'], 'articles__articletags_article_id_tag_id_unique');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('Articles__ArticleTags');
	}

}
