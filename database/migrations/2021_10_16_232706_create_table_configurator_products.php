<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableConfiguratorProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Configuration__Products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('configuration_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->tinyInteger('quantity')->unsigned();

            $table->foreign('configuration_id')->references('id')->on('Configuration__Main')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE')
            ;

            $table->foreign('product_id')->references('id')->on('Products')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE')
            ;

            $table->unique(['configuration_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Configuration__Products');
    }
}
