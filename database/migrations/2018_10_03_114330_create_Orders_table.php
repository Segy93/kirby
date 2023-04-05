<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('Orders', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned()->index('orders_user_id_foreign');
			$table->integer('payment_method_id')->unsigned()->index('orders_payment_method_id_foreign');
			$table->integer('delivery_address_id')->unsigned()->index('orders_delivery_address_id_foreign');
			$table->integer('billing_address_id')->unsigned()->nullable()->index('orders_billing_address_id_foreign');
			$table->string('online_token')->nullable();
			$table->timestamp('date_order')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->dateTime('date_delivery')->nullable();
			$table->text('note', 65535)->nullable();
			$table->integer('total_price')->default(0);
			$table->integer('shipping_fee')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('Orders');
	}

}
