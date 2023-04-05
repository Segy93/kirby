<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertPermissionsForBannerService extends Migration
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
                    'machine_name'  =>  'banner_create',
                    'description'   =>  'Kreiranje banera',
                ],
                [
                    'machine_name'  =>  'banner_update',
                    'description'   =>  'Izmena banera',
                ],
                [
                    'machine_name'  =>  'banner_delete',
                    'description'   =>  'Brisanje banera',
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
            'banner_create',
            'banner_update',
            'banner_delete',
        ];
        
        DB::table('Permissions')->whereIn('machine_name', $permissions_to_delete)->delete();
    }
}
