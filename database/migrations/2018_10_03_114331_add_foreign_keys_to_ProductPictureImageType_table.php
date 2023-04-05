<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToProductPictureImageTypeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('ProductPictureImageType', function(Blueprint $table)
		{
			$table->foreign('picture_id')->references('id')->on('ProductPictures')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('type_id')->references('id')->on('ImageType')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('ProductPictureImageType', function(Blueprint $table)
		{
			$table->dropForeign('productpictureimagetype_picture_id_foreign');
			$table->dropForeign('productpictureimagetype_type_id_foreign');
		});
	}

}
