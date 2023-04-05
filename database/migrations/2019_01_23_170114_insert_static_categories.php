<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertStaticCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('StaticPages__Categories')->insert(
            [
                'name'          =>  'Info',
            ]
        );


        DB::table('StaticPages__Categories')->insert(
            [
                'name'          =>  'Usluge',
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
        DB::table('StaticPages__Categories')->where('name', 'Info')->delete();
        DB::table('StaticPages__Categories')->where('name', 'Usluge')->delete();
    }
}
