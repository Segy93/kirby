<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRolePermissionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('RolePermissions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('role_id')->unsigned();
			$table->integer('permission_id')->unsigned()->index('rolepermissions_permission_id_foreign');
			$table->index(['role_id','permission_id'], 'rolepermissions_role_id_permission_id_index');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('RolePermissions');
	}

}
