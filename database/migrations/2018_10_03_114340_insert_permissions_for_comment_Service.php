<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertPermissionsForCommentService extends Migration
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
                    'machine_name'  =>  'comment_create',
                    'description'   =>  'Kreiranje komentara',
                ],
                [
                    'machine_name'  =>  'comment_read',
                    'description'   =>  'Pretraga komentara',
                ],
                [
                    'machine_name'  =>  'comment_update',
                    'description'   =>  'Izmena komentara',
                ],
                [
                    'machine_name'  =>  'comment_delete',
                    'description'   =>  'Brisanje komentara',
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
            'comment_create', 
            'comment_read', 
            'comment_update', 
            'comment_delete',
        ];

        DB::table('Permissions')->whereIn('machine_name', $permissions_to_delete)->delete();
    }
}
