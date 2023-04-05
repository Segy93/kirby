<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertPermissionsForTagService extends Migration
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
                    'machine_name'  =>  'tag_create',
                    'description'   =>  'Kreiranje taga',
                ],
                [
                    'machine_name'  =>  'tag_read',
                    'description'   =>  'Pretraga tagova',
                ],
                [
                    'machine_name'  =>  'tag_update',
                    'description'   =>  'Izmena taga',
                ],
                [
                    'machine_name'  =>  'tag_delete',
                    'description'   =>  'Brisanje taga',
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
            'tag_create',
            'tag_read',
            'tag_update',
            'tag_delete',
        ];

        DB::table('Permissions')->whereIn('machine_name', $permissions_to_delete)->delete();
    }
}
