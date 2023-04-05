<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToAttributeValuesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('AttributeValues', function(Blueprint $table)
		{
			$table->foreign('attribute_id')->references('id')->on('Attributes')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('AttributeValues', function(Blueprint $table)
		{
			$table->dropForeign('attributevalues_attribute_id_foreign');
		});
	}

}
