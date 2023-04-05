<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToAttributesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('Attributes', function(Blueprint $table)
		{
			$table->foreign('category_id')->references('id')->on('Categories')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('Attributes', function(Blueprint $table)
		{
			$table->dropForeign('attributes_category_id_foreign');
		});
	}

}
