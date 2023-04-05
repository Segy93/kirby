<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertPermissionsForShopUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('Permissions')->insert(
            [
                [
                    'machine_name'  =>  'shop_update',
                    'description'   =>  'Ažuriranje radnje',
                ]
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
        DB::table('Permissions')->where('machine_name', 'shop_update')->delete();
    }
}
