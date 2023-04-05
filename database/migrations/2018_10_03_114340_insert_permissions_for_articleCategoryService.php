<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertPermissionsForArticleCategoryService extends Migration
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
                    'machine_name'  =>  'articleCategory_create',
                    'description'   =>  'Kreiranje kategorije članaka',
                ],
                [
                    'machine_name'  =>  'articleCategory_read',
                    'description'   =>  'Pretraga kategorija članaka',
                ],
                [
                    'machine_name'  =>  'articleCategory_update',
                    'description'   =>  'Izmena kategorije članaka',
                ],
                [
                    'machine_name'  =>  'articleCategory_delete',
                    'description'   =>  'Brisanje kategorija članaka',
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
        [
            'articleCategory_create',
            'articleCategory_read',
            'articleCategory_update',
            'articleCategory_delete',
        ];
        
        DB::table('Permissions')->whereIn('machine_name', $permissions_to_delete)->delete();
    }
}
