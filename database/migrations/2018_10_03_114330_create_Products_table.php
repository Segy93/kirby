<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('Products', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('artid')->index('artid')->unsigned();
			$table->integer('category_id')->unsigned()->index('products_category_id_foreign');
			$table->string('name', 127)->index('full');
			$table->text('description', 65535)->nullable();
			$table->integer('price_retail')->unsigned();
			$table->integer('price_discount')->unsigned();
			$table->integer('voucher')->unsigned();
			$table->integer('rating_sum')->unsigned()->default(0);
			$table->integer('rating_count')->unsigned()->default(0);
			$table->boolean('on_sale');
			$table->boolean('is_featured');
			$table->boolean('presales');
			$table->boolean('published');
			$table->boolean('stock_warehouse');
			$table->integer('weight')->nullable();
			$table->integer('width')->nullable();
			$table->integer('height')->nullable();
			$table->integer('length')->nullable();
			$table->string('ean', 13)->nullable();
			$table->longText('youtube')->nullable();
			$table->string('link')->nullable();
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('Products');
	}

}
