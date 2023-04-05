<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAddressesUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('Addresses__User', function(Blueprint $table)
		{
			$table->integer('id')->unsigned()->unique('addresses__user_id_unique');
			$table->integer('user_id')->unsigned()->index('addresses__user_user_id_foreign')->onDelete('cascade');;
			$table->string('contact_name', 63);
			$table->string('contact_surname', 63);
			$table->string('company', 63)->nullable();
			$table->string('phone_nr', 63);
			$table->tinyInteger('status')->nullable(false)->unsigned()->default(0);
			$table->boolean('preferred_address_delivery')->default(0);
			$table->boolean('preferred_address_billing')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('Addresses__User');
	}

}
