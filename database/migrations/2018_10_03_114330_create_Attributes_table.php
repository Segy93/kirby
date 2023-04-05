<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAttributesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('Attributes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('category_id')->unsigned()->index('attributes_category_id_foreign');
			$table->string('machine_name', 63);
			$table->string('name_import', 63);
			$table->string('label', 63);
			$table->string('type', 31)->nullable();
			$table->boolean('order_category')->nullable();
			$table->boolean('order_filter')->nullable();
			$table->boolean('order_product')->nullable();
			$table->boolean('order_url')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('Attributes');
	}

}
