<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCommentsMainTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('Comments__Main', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned()->index('comments__main_user_id_foreign');
			$table->integer('parent_id')->unsigned()->nullable()->index('comments__main_parent_id_foreign');
			$table->text('text', 65535);
			$table->boolean('approved')->default(0);
			$table->timestamp('date')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->string('discr', 15);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('Comments__Main');
	}

}
