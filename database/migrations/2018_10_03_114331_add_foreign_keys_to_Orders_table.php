<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('Orders', function(Blueprint $table)
		{
			$table->foreign('billing_address_id')->references('id')->on('Addresses__Main')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('delivery_address_id')->references('id')->on('Addresses__Main')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('payment_method_id')->references('id')->on('PaymentMethods')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('user_id')->references('id')->on('Users')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('Orders', function(Blueprint $table)
		{
			$table->dropForeign('orders_billing_address_id_foreign');
			$table->dropForeign('orders_delivery_address_id_foreign');
			$table->dropForeign('orders_payment_method_id_foreign');
			$table->dropForeign('orders_user_id_foreign');
		});
	}

}
