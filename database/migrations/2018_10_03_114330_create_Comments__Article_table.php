<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCommentsArticleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('Comments__Article', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->unique('comments__article_id_unique');
			$table->integer('article_id')->unsigned()->index('comments__article_article_id_foreign');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('Comments__Article');
	}

}
