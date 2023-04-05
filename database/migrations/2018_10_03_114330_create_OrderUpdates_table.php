<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderUpdatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('OrderUpdates', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('order_id')->unsigned()->index('orderstatuschanges_order_id_foreign');
			$table->integer('admin_id')->unsigned()->nullable()->index('orderstatuschanges_admin_id_foreign');
			$table->text('comment_admin', 65535)->nullable();
			$table->text('comment_user', 65535)->nullable();
			$table->boolean('user_notified')->default(0);
			$table->boolean('status_code')->default(0);
			$table->timestamp('date')->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('OrderUpdates');
	}

}
