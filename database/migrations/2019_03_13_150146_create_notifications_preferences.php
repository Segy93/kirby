<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsPreferences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Notifications__Preferences', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index('notifications_preferences_user_id_index')->onDelete('cascade');
            $table->string('device', 255);
            $table->string('endpoint', 511);
            $table->string('p256dh', 511);
            $table->string('auth', 511);
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Notifications__Preferences');
    }
}
