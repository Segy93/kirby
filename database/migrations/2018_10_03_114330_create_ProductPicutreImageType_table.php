<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductPicutreImageTypeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ProductPictureImageType', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('picture_id')->unsigned()->index('product_picture_foreign');
			$table->integer('type_id')->unsigned()->index('image_type_foreign');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ProductPictureImageType');
	}

}
