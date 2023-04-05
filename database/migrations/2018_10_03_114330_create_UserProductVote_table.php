<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserProductVoteTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('UserProductVote', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('product_id')->unsigned();
			$table->integer('user_id')->unsigned()->nullable()->index('userproductvote_user_id_foreign');
			$table->string('ip_address', 16)->nullable();
			$table->boolean('vote');
			$table->unique(['product_id','ip_address'], 'userproductvote_product_id_ip_address_unique');
			$table->unique(['product_id','user_id'], 'userproductvote_product_id_user_id_unique');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('UserProductVote');
	}

}
