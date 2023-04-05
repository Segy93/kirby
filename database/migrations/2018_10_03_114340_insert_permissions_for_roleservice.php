<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertPermissionsForRoleservice extends Migration
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
                    'machine_name'  =>  'role_create',
                    'description'   =>  'Kreiranje uloge',
                ],
                [
                    'machine_name'  =>  'role_read',
                    'description'   =>  'Pretraga uloga',
                ],
                [
                    'machine_name'  =>  'role_update',
                    'description'   =>  'Izmena uloge',
                ],
                [
                    'machine_name'  =>  'role_delete',
                    'description'   =>  'Brisanje uloge',
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
            'role_create',
            'role_read',
            'role_update',
            'role_delete',
        ];
        
        DB::table('Permissions')->whereIn('machine_name', $permissions_to_delete)->delete();
    }
}
