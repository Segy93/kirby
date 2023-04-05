<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBannersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('Banners', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('position_id')->unsigned()->index('banners_position_id_foreign');
			$table->string('title', 127)->unique('banners_title_unique');
			$table->string('image');
			$table->string('link');
			$table->string('urls');
			$table->integer('nr_clicks')->default(0);
			$table->boolean('status')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('Banners');
	}

}
