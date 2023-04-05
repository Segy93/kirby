<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToProductPicturesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('ProductPictures', function(Blueprint $table)
		{
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
		Schema::table('ProductPictures', function(Blueprint $table)
		{
			$table->dropForeign('productpictures_product_id_foreign');
		});
	}

}
