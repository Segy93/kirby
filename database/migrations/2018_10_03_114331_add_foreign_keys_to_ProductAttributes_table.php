<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToProductAttributesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('ProductAttributes', function(Blueprint $table)
		{
			$table->foreign('attribute_value_id')->references('id')->on('AttributeValues')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('product_id')->references('id')->on('Products')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('ProductAttributes', function(Blueprint $table)
		{
			$table->dropForeign('productattributes_attribute_value_id_foreign');
			$table->dropForeign('productattributes_product_id_foreign');
		});
	}

}
