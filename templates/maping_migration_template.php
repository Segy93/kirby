<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ||category_name||CategoryMapping extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        ##################################
        #      Unos kategorije           #
        ##################################
        DB::table('Categories')->insert(
            [
                'name'          =>  '||category_name_raw||',
                'name_import'   =>  '||category_name_import||',
            ]
        );

        ##################################
        # Dohvatanje kreirane kategorije #
        ##################################
        $category = DB::table('Categories')
            ->where('name', '=', '||category_name_raw||')
            ->first()
        ;

        $category_id = $category->id;

        #################################
        # Kreiranje seo-a za kategoriju #
        #################################

        DB::table('SEO')->insert(
            [
                'machine_name'  =>  'category_' . $category_id,
                'url'           =>  '||category_url||',
            ]
        );

        ###############################
        #       Unos atributa         #
        ###############################

        ||category_attributes||

        ||foreach_attribute_start||
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  '||attribute_machine_name||',
                'name_import'       =>  '||attribute_name_import||',
                'label'             =>  '||attribute_label||',
                'type'              =>  '||attribute_type||',
                'order_category'    =>  ||attribute_order_category||,
                'order_filter'      =>  ||attribute_order_filter||,
                'order_product'     =>  ||attribute_order_product||,
            ]
        );
        ||foreach_attribute_end||
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        $category = DB::table('Categories')
        ->where('name', '=', '||category_name||')
        ->first();

        $category_id = $category->id;

        DB::table('Attributes')->where('category_id', '=', $category_id)->delete();
        DB::table('SEO')->where('machine_name', '=', 'category_' . $category_id)->delete();
        DB::table('Categories')->where('id', '=', $category_id)->delete();

    }
}
