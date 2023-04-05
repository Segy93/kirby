<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index('notifications_user_id_index')->onDelete('cascade');
            $table->integer('type_id')->unsigned()->index('notifications_type_id_index')->onDelete('cascade');
            $table->string('subject', 127);
            $table->string('message', 255);
            $table->tinyInteger('is_read')->unsigned()->default(0)->index('notifications_is_read_index');
            $table->timestamp('expires')->nullable();
            $table->index(['is_read', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Notifications');
    }
}
