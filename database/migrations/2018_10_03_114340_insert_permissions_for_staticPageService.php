<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertPermissionsForStaticPageService extends Migration
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
                    'machine_name'  =>  'staticPage_create',
                    'description'   =>  'Kreiranje statičkih strana',
                ],
                [
                    'machine_name'  =>  'staticPage_read',
                    'description'   =>  'Pretraga statičkih strana',
                ],
                [
                    'machine_name'  =>  'staticPage_update',
                    'description'   =>  'Izmena statičkih strana',
                ],
                [
                    'machine_name'  =>  'staticPage_delete',
                    'description'   =>  'Brisanje statičkih strana',
                ],
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
        [   'staticPage_create', 
            'staticPage_read', 
            'staticPage_update', 
            'staticPage_delete',
        ];

        DB::table('Permissions')->whereIn('machine_name', $permissions_to_delete)->delete();
    }
}
