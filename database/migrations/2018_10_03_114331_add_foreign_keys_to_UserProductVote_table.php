<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToUserProductVoteTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('UserProductVote', function(Blueprint $table)
		{
			$table->foreign('product_id')->references('id')->on('Products')->onUpdate('RESTRICT')->onDelete('RESTRICT');
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
		Schema::table('UserProductVote', function(Blueprint $table)
		{
			$table->dropForeign('userproductvote_product_id_foreign');
			$table->dropForeign('userproductvote_user_id_foreign');
		});
	}

}
