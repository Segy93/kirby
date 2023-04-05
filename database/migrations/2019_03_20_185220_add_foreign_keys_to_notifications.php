<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Notifications', function (Blueprint $table) {
            
            $table->foreign('user_id')->references('id')->on('Users')->onUpdate('RESTRICT')->onDelete('CASCADE'); 
            $table->foreign('type_id')->references('id')->on('Notifications__Types')->onUpdate('RESTRICT')->onDelete('CASCADE'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Notifications', function (Blueprint $table) {
            
			$table->dropForeign('notifications_user_id_foreign');
			$table->dropForeign('notifications_type_id_foreign');
        });
    }
}
