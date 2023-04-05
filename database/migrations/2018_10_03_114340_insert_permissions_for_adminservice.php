<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertPermissionsForAdminservice extends Migration
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
                    'machine_name'  =>  'admin_create',
                    'description'   =>  'Kreiranje administratora',
                ],
                [
                    'machine_name'  =>  'admin_read',
                    'description'   =>  'Pretraga administratora',
                ],
                [
                    'machine_name'  =>  'admin_update',
                    'description'   =>  'Izmena administratora',
                ],
                [
                    'machine_name'  =>  'admin_delete',
                    'description'   =>  'Brisanje administratora',
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
            'admin_create',
            'admin_read',
            'admin_update',
            'admin_delete',
        ];
        
        DB::table('Permissions')->whereIn('machine_name', $permissions_to_delete)->delete();
    }
}
