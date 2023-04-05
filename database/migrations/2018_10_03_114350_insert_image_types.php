<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertImageTypes extends Migration
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
                'name'   => 'thumbnail',
                'width'  => 200,
                'height' => 200,
            ]
        );

        DB::table('ImageType')->insert(
            [
                'name'   => 'product_page_image',
                'width'  => 320,
                'height' => 266,
            ]
        );

        DB::table('ImageType')->insert(
            [
                'name'   => 'full_width',
                'width'  => 1000,
                'height' => 1000,
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
        DB::table('ImageType')->where('name', 'thumbnail')->delete();
        DB::table('ImageType')->where('name', 'product_page_image')->delete();

    }
}