<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCommentsProductTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('Comments__Product', function(Blueprint $table)
		{
			$table->foreign('id')->references('id')->on('Comments__Main')->onUpdate('RESTRICT')->onDelete('CASCADE');
			$table->foreign('product_id')->references('id')->on('Products')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('Comments__Product', function(Blueprint $table)
		{
			$table->dropForeign('comments__product_id_foreign');
			$table->dropForeign('comments__product_product_id_foreign');
		});
	}

}
