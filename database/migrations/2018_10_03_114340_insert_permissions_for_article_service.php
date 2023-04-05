<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertPermissionsForArticleService extends Migration
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
                    'machine_name'  =>  'article_create',
                    'description'   =>  'Kreiranje članka',
                ],
                [
                    'machine_name'  =>  'article_read',
                    'description'   =>  'Pretraga članaka',
                ],
                [
                    'machine_name'  =>  'article_update',
                    'description'   =>  'Izmena članka',
                ],
                [
                    'machine_name'  =>  'article_delete',
                    'description'   =>  'Brisanje članka',
                ],
                [
                    'machine_name'  =>  'article_update_author',
                    'description'   =>  'Menjanje autora članka',
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
            'article_create',
            'article_read',
            'article_update',
            'article_delete',
            'article_update_author',
        ];
        
        DB::table('Permissions')->whereIn('machine_name', $permissions_to_delete)->delete();
    }
}
