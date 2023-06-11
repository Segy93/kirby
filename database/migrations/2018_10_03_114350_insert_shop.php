<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertShop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('Shops')->insert(
            [
                'name'          =>  'Kraljice Katarine 55, Čukarica',
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        DB::table('Shops')->where('name', 'Kraljice Katarine 55, Čukarica')->delete();
    }
}
