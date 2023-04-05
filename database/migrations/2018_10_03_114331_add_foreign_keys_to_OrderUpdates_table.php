<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToOrderUpdatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('OrderUpdates', function(Blueprint $table)
		{
			$table->foreign('admin_id', 'orderstatuschanges_admin_id_foreign')->references('id')->on('Admins')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('order_id', 'orderstatuschanges_order_id_foreign')->references('id')->on('Orders')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('OrderUpdates', function(Blueprint $table)
		{
			$table->dropForeign('orderstatuschanges_admin_id_foreign');
			$table->dropForeign('orderstatuschanges_order_id_foreign');
		});
	}

}
