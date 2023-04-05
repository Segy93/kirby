<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTypeTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Notifications__Types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 127)->index('notifications_type_name_index')->unique();
            $table->string('machine_name', 127)->index('notifications_type_machine_name_index')->unique();
            $table->tinyInteger('position')->unsigned();
        });
    }

//     id (int unsigned, not null, primary key, auto increment)
//     name (varchar (63), not null, unique)
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Notifications__Types');
    }
}
