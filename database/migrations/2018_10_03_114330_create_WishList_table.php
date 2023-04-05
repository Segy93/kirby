<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWishListTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('WishList', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('product_id')->unsigned();
			$table->integer('user_id')->unsigned()->index('wishlist_user_id_index')->onDelete('cascade');
			$table->unique(['product_id','user_id'], 'wishlist_product_id_user_id_unique');
			$table->index(['product_id','user_id'], 'wishlist_product_id_user_id_index');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('WishList');
	}

}
