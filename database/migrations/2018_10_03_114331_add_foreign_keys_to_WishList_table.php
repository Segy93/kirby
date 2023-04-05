<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToWishListTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('WishList', function(Blueprint $table)
		{
			$table->foreign('product_id')->references('id')->on('Products')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('user_id')->references('id')->on('Users')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('WishList', function(Blueprint $table)
		{
			$table->dropForeign('wishlist_product_id_foreign');
			$table->dropForeign('wishlist_user_id_foreign');
		});
	}

}
