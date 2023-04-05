<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPhoneNumbersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('PhoneNumbers', function(Blueprint $table)
		{
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
		Schema::table('PhoneNumbers', function(Blueprint $table)
		{
			$table->dropForeign('phonenumbers_shop_id_foreign');
		});
	}

}
