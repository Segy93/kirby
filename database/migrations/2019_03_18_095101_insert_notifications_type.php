<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertNotificationsType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('Notifications__Types')->insert(
            [
                'name'          =>  'Komentar je odgovoren',
                'machine_name'  =>  'comment_answered',
                'position'      =>   1,
            ]
        );


        DB::table('Notifications__Types')->insert(
            [
                'name'          =>  'Narudžbina je promenjena',
                'machine_name'  =>  'order_updated',
                'position'      =>   2,
            ]
        );


        DB::table('Notifications__Types')->insert(
            [
                'name'          =>  'Status narudžbine je promenjen',
                'machine_name'  =>  'order_status_updated',
                'position'      =>   3,
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

        DB::table('Notifications__Types')->where('machine_name', 'comment_answered')->delete();
        DB::table('Notifications__Types')->where('machine_name', 'order_updated')->delete();
        DB::table('Notifications__Types')->where('machine_name', 'order_status_updated')->delete();
    }
}
