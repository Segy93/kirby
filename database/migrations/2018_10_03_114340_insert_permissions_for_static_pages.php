<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertPermissionsForStaticPages extends Migration
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
                    'machine_name'  =>  'category_static_create',
                    'description'   =>  'Kreiranje static kategorije',
                ],
                [
                    'machine_name'  =>  'category_static_read',
                    'description'   =>  'Pregled static kategorija',
                ],
                [
                    'machine_name'  =>  'category_static_update',
                    'description'   =>  'Izmena static kategorija',
                ],
                [
                    'machine_name'  =>  'category_static_delete',
                    'description'   =>  'Brisanje static kategorija',
                ],
                [
                    'machine_name'  =>  'static_page_create',
                    'description'   =>  'Kreiranje static strane',
                ],
                [
                    'machine_name'  =>  'static_page_read',
                    'description'   =>  'Prikaz static strane',
                ],
                [
                    'machine_name'  =>  'static_page_update',
                    'description'   =>  'Izmena static strane',
                ],
                [
                    'machine_name'  =>  'static_page_delete',
                    'description'   =>  'Brisanje static strane',
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
            'category_static_create',
            'category_static_read',
            'category_static_read',
            'category_static_delete',
            'static_page_create',
            'static_page_read',
            'static_page_update',
            'static_page_delete',
        ];
        
        DB::table('Permissions')->whereIn('machine_name', $permissions_to_delete)->delete();
    }
}
