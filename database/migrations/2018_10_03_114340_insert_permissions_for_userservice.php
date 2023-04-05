<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertPermissionsForUserservice extends Migration
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
                    'machine_name'  =>  'user_read',
                    'description'   =>  'Pretraga korisnika',
                ],
                [
                    'machine_name'  =>  'user_update',
                    'description'   =>  'Izmena korisnika',
                ],
                [
                    'machine_name'  =>  'user_delete',
                    'description'   =>  'Brisanje korisnika',
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
            'user_read',
            'user_update',
            'user_delete',
        ];
        
        DB::table('Permissions')->whereIn('machine_name', $permissions_to_delete)->delete();
    }
}
