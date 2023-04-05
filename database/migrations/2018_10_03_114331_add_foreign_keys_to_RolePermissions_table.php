<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToRolePermissionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('RolePermissions', function(Blueprint $table)
		{
			$table->foreign('permission_id')->references('id')->on('Permissions')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('role_id')->references('id')->on('Roles')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('RolePermissions', function(Blueprint $table)
		{
			$table->dropForeign('rolepermissions_permission_id_foreign');
			$table->dropForeign('rolepermissions_role_id_foreign');
		});
	}

}
