<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToNotificationsSubscriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Notifications__Subscriptions', function (Blueprint $table) {
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
        Schema::table('Notifications__Subscriptions', function (Blueprint $table) {
			$table->dropForeign('notifications__subscriptions_user_id_foreign');
			$table->dropForeign('notifications__subscriptions_type_id_foreign');
        });
    }
}
