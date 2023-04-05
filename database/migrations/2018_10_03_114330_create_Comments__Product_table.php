<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCommentsProductTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('Comments__Product', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->unique('comments__product_id_unique');
			$table->integer('product_id')->unsigned()->index('comments__product_product_id_foreign');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('Comments__Product');
	}

}
