<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Notifications__Subscriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index('notifications_subscriptions_user_id_index')->onDelete('cascade');
            $table->integer('type_id')->unsigned()->index('notifications_subscriptions_type_id_index')->onDelete('cascade');
			$table->unique(['user_id','type_id'], 'user_id_type_id__subscription_unique');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Notifications__Subscriptions');
    }
}
