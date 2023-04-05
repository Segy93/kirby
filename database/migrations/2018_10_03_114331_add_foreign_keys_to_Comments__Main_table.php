<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCommentsMainTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('Comments__Main', function(Blueprint $table)
		{
			$table->foreign('parent_id')->references('id')->on('Comments__Main')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('user_id')->references('id')->on('Users')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('Comments__Main', function(Blueprint $table)
		{
			$table->dropForeign('comments__main_parent_id_foreign');
			$table->dropForeign('comments__main_user_id_foreign');
		});
	}

}
