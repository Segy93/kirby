<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCartTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('Cart', function(Blueprint $table)
		{
			$table->foreign('product_id')->references('id')->on('Products')->onUpdate('RESTRICT')->onDelete('CASCADE');
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
		Schema::table('Cart', function(Blueprint $table)
		{
			$table->dropForeign('cart_product_id_foreign');
			$table->dropForeign('cart_user_id_foreign');
		});
	}

}
