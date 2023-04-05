<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class bundlekitCategoryMapping2 extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        ##################################
        # Dohvatanje kreirane kategorije #
        ##################################
        $category = DB::table('Categories')
            ->where('name', '=', 'Bundle kit')
            ->first()
        ;

        $category_id = $category->id;



        ###############################
        #       Unos atributa         #
        ###############################

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_boja')
            ->update([
                'order_product'     =>  22,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_bundle_brzina_memorije')
            ->update([
                'order_product'     =>  8,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_dodatne_opcije')
            ->update([
                'order_product'     =>  23,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_ean_kod')
            ->update([
                'order_product'     =>  26,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_garantni_rok')
            ->update([
                'order_product'     =>  24,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_graficka_karta')
            ->update([
                'order_product'     =>  15,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_model_procesora')
            ->update([
                'order_product'     =>  1,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_model_hard_diska')
            ->update([
                'order_product'     =>  5,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_model_maticne_ploce')
            ->update([
                'order_product'     =>  4,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_model_memorije')
            ->update([
                'order_product'     =>  7,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_model_optickog_uredjaja')
            ->update([
                'order_product'     =>  10,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_model_ssd_a')
            ->update([
                'order_product'     =>  9,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_monitor')
            ->update([
                'order_product'     =>  18,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_procesorski_hladnjak')
            ->update([
                'order_product'     =>  3,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_bundle_proizvodjac_grafike')
            ->update([
                'order_product'     =>  13,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_bundle_proizvodjac_gpu')
            ->update([
                'order_product'     =>  14,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_bundle_proizvodjac_kucista')
            ->update([
                'order_product'     =>  11,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_model_kucista')
            ->update([
                'order_product'     =>  12,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_napajanje')
            ->update([
                'order_product'     =>  17,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_bundle_kit_os')
            ->update([
                'order_product'     =>  19,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_bundle_oznaka_procesora')
            ->update([
                'order_product'     =>  2,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_bundle_tip_memorije')
            ->update([
                'order_product'     =>  6,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_bundle_tip_os')
            ->update([
                'order_product'     =>  20,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_youtube')
            ->update([
                'order_product'     =>  25,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_zvucna_karta')
            ->update([
                'order_product'     =>  16,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {

        ##################################
        # Dohvatanje kreirane kategorije #
        ##################################
        $category = DB::table('Categories')
            ->where('name', '=', 'Bundle kit')
            ->first()
        ;

        $category_id = $category->id;



        ###############################
        #       Unos atributa         #
        ###############################

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_boja')
            ->update([
                'order_product'     =>  null,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_bundle_brzina_memorije')
            ->update([
                'order_product'     =>  null,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_dodatne_opcije')
            ->update([
                'order_product'     =>  null,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_ean_kod')
            ->update([
                'order_product'     =>  null,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_garantni_rok')
            ->update([
                'order_product'     =>  null,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_graficka_karta')
            ->update([
                'order_product'     =>  null,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_model_procesora')
            ->update([
                'order_product'     =>  null,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_model_hard_diska')
            ->update([
                'order_product'     =>  null,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_model_maticne_ploce')
            ->update([
                'order_product'     =>  null,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_model_memorije')
            ->update([
                'order_product'     =>  null,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_model_optickog_uredjaja')
            ->update([
                'order_product'     =>  null,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_model_ssd_a')
            ->update([
                'order_product'     =>  null,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_monitor')
            ->update([
                'order_product'     =>  null,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_procesorski_hladnjak')
            ->update([
                'order_product'     =>  null,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_bundle_proizvodjac_grafike')
            ->update([
                'order_product'     =>  null,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_bundle_proizvodjac_gpu')
            ->update([
                'order_product'     =>  null,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_bundle_proizvodjac_kucista')
            ->update([
                'order_product'     =>  null,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_model_kucista')
            ->update([
                'order_product'     =>  null,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_napajanje')
            ->update([
                'order_product'     =>  null,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_bundle_kit_os')
            ->update([
                'order_product'     =>  null,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_bundle_oznaka_procesora')
            ->update([
                'order_product'     =>  null,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_bundle_tip_memorije')
            ->update([
                'order_product'     =>  null,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_bundle_tip_os')
            ->update([
                'order_product'     =>  null,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_youtube')
            ->update([
                'order_product'     =>  null,
        ]);

        DB::table('Attributes')
            ->where('category_id', '=', $category_id)
            ->where('machine_name', 'field_zvucna_karta')
            ->update([
                'order_product'     =>  null,
        ]);

    }
}
