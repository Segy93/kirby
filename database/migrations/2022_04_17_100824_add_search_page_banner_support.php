<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddSearchPageBannerSupport extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        DB::table('PageTypes')->insert([
            'type'          => 'Pretraga',
            'machine_name'  => 'search_page',
        ]);

        /** @var ?stdClass */
        $page_type = DB::table('PageTypes')
            ->where('machine_name', '=', 'search_page')
            ->get()
            ->first()
        ;

        if ($page_type === null) {
            return;
        }

        DB::table('Positions')->insert([
            'page_type_id'  => $page_type->id,
            'position'      => 'Iznad rezultata',
            'image_width'   => 1200,
            'image_height'  => 350,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        DB::table('PageTypes')->where('machine_name', 'search_pages')->delete();
    }
}
