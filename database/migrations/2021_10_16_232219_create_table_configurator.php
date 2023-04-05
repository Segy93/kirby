<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableConfigurator extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Configuration__Main', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->index();
            $table->integer('user_id')->unsigned();
            $table->tinyInteger('visibility')->unsigned()->default(0);
            $table->datetime('date_created');
            $table->datetime('date_updated')->nullable();

            $table->foreign('user_id')->references('id')->on('Users')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE')
            ;

            $table->unique(['name', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Configuration__Main');
    }
}
