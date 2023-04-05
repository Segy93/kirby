<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateArticlesMainTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('Articles__Main', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('category_id')->unsigned()->index('articles__main_category_id_foreign');
			$table->integer('author_id')->unsigned()->nullable()->index('articles__main_author_id_foreign');
			$table->string('title', 127)->unique('articles__main_title_unique');
			$table->text('text', 65535);
			$table->text('excerpt', 65535);
			$table->string('picture', 127);
			$table->integer('views');
			$table->boolean('status')->default(1);
			$table->timestamp('published_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('Articles__Main');
	}

}
