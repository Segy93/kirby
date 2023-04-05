<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableAddressesUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Addresses__User', function(Blueprint $table)
		{
			$table->integer('pib')->unsigned()->nullable()->after('company');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Addresses__User', function (Blueprint $table) {
            $table->dropColumn('pib');
        });
    }
}
