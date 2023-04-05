<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAttributeValuesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('AttributeValues', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('attribute_id')->unsigned();
			$table->string('value');
			$table->unique(['attribute_id','value'], 'attributevalues_attribute_id_value_unique');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('AttributeValues');
	}

}
