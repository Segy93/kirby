<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePhoneNumbersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('PhoneNumbers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('shop_id')->unsigned()->index('phonenumbers_shop_id_foreign');
			$table->string('phone_nr', 127);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('PhoneNumbers');
	}

}
