<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymentMethodsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('PaymentMethods', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('method', 31);
			$table->string('label', 127);
			$table->string('description', 255);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('PaymentMethods');
	}

}
