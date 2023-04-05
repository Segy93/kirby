<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImageTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('ImageType')->insert(
            [
                'name'   => 'thumbnail_twitter',
                'width'  => 120,
                'height' => 120,
            ]
        );

        DB::table('ImageType')->insert(
            [
                'name'   => 'image_twitter',
                'width'  => 280,
                'height' => 170,
            ]
        );

        DB::table('ImageType')->insert(
            [
                'name'   => 'image_open_graph',
                'width'  => 1200,
                'height' => 630,
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
        DB::table('ImageType')->where('name', 'thumbnail_twitter')->delete();
        DB::table('ImageType')->where('name', 'image_twitter')->delete();
        DB::table('ImageType')->where('name', 'image_open_graph')->delete();
    }
}
