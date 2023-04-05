<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToNotificationsPreferences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Notifications__Preferences', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('Users')->onUpdate('RESTRICT')->onDelete('CASCADE');  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Notifications__Preferences', function (Blueprint $table) {
            
			$table->dropForeign('notifications__preferences_user_id_foreign');
        });
    }
}
