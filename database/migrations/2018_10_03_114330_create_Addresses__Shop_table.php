<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAddressesShopTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('Addresses__Shop', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->unique('addresses__shop_id_unique');
			$table->integer('shop_id')->unsigned()->unique('addresses__shop_shop_id_unique');
			$table->string('email', 127);
			$table->string('fax', 63);
			$table->string('open_hours');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('Addresses__Shop');
	}

}
