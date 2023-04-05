<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSEOTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('SEO', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('machine_name')->unique('seo_machine_name_unique');
			$table->string('url')->unique('seo_url_unique');
			$table->string('keywords')->nullable();
			$table->string('description')->nullable();
			$table->string('title', 127)->nullable();
			$table->string('thumbnail_twitter')->nullable();
			$table->string('image_twitter')->nullable();
			$table->string('image_open_graph')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('SEO');
	}

}
