<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToOrderProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('OrderProducts', function(Blueprint $table)
		{
			$table->foreign('order_id')->references('id')->on('Orders')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('product_id')->references('id')->on('Products')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('OrderProducts', function(Blueprint $table)
		{
			$table->dropForeign('orderproducts_order_id_foreign');
			$table->dropForeign('orderproducts_product_id_foreign');
		});
	}

}
