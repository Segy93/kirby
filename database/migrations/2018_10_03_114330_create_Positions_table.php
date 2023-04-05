<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePositionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('Positions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('page_type_id')->unsigned();
			$table->string('position', 63)->unique('positions_position_unique');
			$table->smallInteger('image_width')->nullable();
			$table->smallInteger('image_height')->nullable();
			$table->index(['page_type_id','position'], 'positions_page_type_id_position_index');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('Positions');
	}

}
