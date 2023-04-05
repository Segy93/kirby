<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToAddressesShopTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('Addresses__Shop', function(Blueprint $table)
		{
			$table->foreign('id')->references('id')->on('Addresses__Main')->onUpdate('RESTRICT')->onDelete('CASCADE');
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
		Schema::table('Addresses__Shop', function(Blueprint $table)
		{
			$table->dropForeign('addresses__shop_id_foreign');
			$table->dropForeign('addresses__shop_shop_id_foreign');
		});
	}

}
