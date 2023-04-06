<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class InsertBannerPagesAndPositions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('PageTypes')->insert(
            [
            'type'          => 'Sve',
            'machine_name'  => 'all_pages'
            ]
        );

        DB::table('PageTypes')->insert(
            [
                'type'          => 'Početna',
                'machine_name'  => 'landing_page'
            ]
        );

        DB::table('PageTypes')->insert(
            [
                'type'          => 'Kategorija proizvoda',
                'machine_name'  => 'product_category'
            ]
        );

        DB::table('PageTypes')->insert(
            [
                'type'          => 'Proizvod',
                'machine_name'  => 'product'
            ]
        );

        DB::table('PageTypes')->insert(
            [
                'type'          => 'Članak',
                'machine_name'  => 'article'
            ]
        );

        DB::table('PageTypes')->insert(
            [
                'type'          => 'Lista članaka',
                'machine_name'  => 'article_list'
            ]
        );

        DB::table('PageTypes')->insert(
            [
                'type'          => 'Profil',
                'machine_name'  => 'profile'
            ]
        );

        DB::table('PageTypes')->insert(
            [
                'type'          => 'Prijava',
                'machine_name'  => 'login'
            ]
        );

        DB::table('PageTypes')->insert(
            [
                'type'          => 'Statička strana',
                'machine_name'  => 'static_page'
            ]
        );


        $all = DB::table('PageTypes')
            ->where('type', '=', 'Sve')
            ->first();

        $index = DB::table('PageTypes')
            ->where('type', '=', 'Početna')
            ->first();

        $category = DB::table('PageTypes')
            ->where('type', '=', 'Kategorija proizvoda')
            ->first();

        $product = DB::table('PageTypes')
            ->where('type', '=', 'Proizvod')
            ->first();

        DB::table('Positions')->insert(
            [
                'page_type_id'  => $all->id,
                'position'      => 'Pozadina',
                'image_width'   => 350,
                'image_height'  => 500
            ]
        );
        DB::table('Positions')->insert(
            [
                'page_type_id'  => $index->id,
                'position'      => 'Slajder',
                'image_width'   => 900,
                'image_height'  => 350
            ]
        );
        DB::table('Positions')->insert(
            [
                'page_type_id'  => $index->id,
                'position'      => 'Početna strana baner',
                'image_width'   => 1200,
                'image_height'  => 350
            ]
        );
        DB::table('Positions')->insert(
            [
                'page_type_id'  => $category->id,
                'position'      => 'Svaki peti u listi',
                'image_width'   => 840,
                'image_height'  => 280
            ]
        );
        DB::table('Positions')->insert(
            [
                'page_type_id'  => $product->id,
                'position'      => 'Ispod podataka',
                'image_width'   => 1200,
                'image_height'  => 350
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

        DB::table('Positions')->where('position', 'Pozadina')->delete();
        DB::table('Positions')->where('position', 'Slajder')->delete();
        DB::table('Positions')->where('position', 'Početna strana baner')->delete();
        DB::table('Positions')->where('position', 'Svaki peti u listi')->delete();
        DB::table('Positions')->where('position', 'Ispod podataka')->delete();
        DB::table('PageTypes')->where('type', 'Sve')->delete();
        DB::table('PageTypes')->where('type', 'Početna')->delete();
        DB::table('PageTypes')->where('type', 'Kategorija')->delete();
        DB::table('PageTypes')->where('type', 'Proizvod')->delete();

    }
}
