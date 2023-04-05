<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('OrderProducts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('order_id')->unsigned()->index('orderproducts_order_id_foreign');
			$table->integer('product_id')->unsigned()->index('orderproducts_product_id_foreign');
			$table->smallInteger('quantity')->unsigned();
			$table->integer('price')->unsigned();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('OrderProducts');
	}

}
