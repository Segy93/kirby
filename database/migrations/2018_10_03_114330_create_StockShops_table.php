<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStockShopsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('StockShops', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('shop_id')->unsigned()->index('stockshops_shop_id_foreign');
			$table->integer('product_id')->unsigned()->index('stockshops_product_id_foreign');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('StockShops');
	}

}
