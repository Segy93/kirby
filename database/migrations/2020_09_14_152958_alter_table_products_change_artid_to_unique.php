<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableProductsChangeArtidToUnique extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Products', function(Blueprint $table)
        {
            $table->integer('artid')->index('artid')->unsigned()->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Products', function (Blueprint $table) {
            $table->dropUnique('products_artid_unique');
        });
    }
}
