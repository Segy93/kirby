<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertPermissionsForShopservice extends Migration
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
                    'machine_name'  =>  'order_read',
                    'description'   =>  'Pretraga narudžbina',
                ],
                [
                    'machine_name'  =>  'order_update',
                    'description'   =>  'Izmena narudžbine',
                ],
                [
                    'machine_name'  =>  'order_delete',
                    'description'   =>  'Brisanje narudžbina',
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
        $permissions_to_delete = 
        [
            'order_read',
            'order_update',
            'order_delete',
        ];
        
        DB::table('Permissions')->whereIn('machine_name', $permissions_to_delete)->delete();
    }
}
