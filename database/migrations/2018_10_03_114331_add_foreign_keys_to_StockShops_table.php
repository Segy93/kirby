<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToStockShopsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('StockShops', function(Blueprint $table)
		{
			$table->foreign('product_id')->references('id')->on('Products')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('shop_id')->references('id')->on('Shops')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('StockShops', function(Blueprint $table)
		{
			$table->dropForeign('stockshops_product_id_foreign');
			$table->dropForeign('stockshops_shop_id_foreign');
		});
	}

}
