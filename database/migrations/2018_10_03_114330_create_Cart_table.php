<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCartTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('Cart', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned()->index('cart_user_id_foreign')->onDelete('cascade');
			$table->integer('product_id')->unsigned();
			$table->smallInteger('quantity')->unsigned();
			$table->unique(['product_id','user_id'], 'cart_product_id_user_id_unique');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('Cart');
	}

}
