<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class dvdblurayplayerCategoryMapping extends Migration {
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
                'name'          =>  'DVD/BluRay Player',
                'name_import'   =>  'DVD/BR PL.',
            ]
        );

        ##################################
        # Dohvatanje kreirane kategorije #
        ##################################
        $category = DB::table('Categories')
            ->where('name', '=', 'DVD/BluRay Player')
            ->first()
        ;

        $category_id = $category->id;

        #################################
        # Kreiranje seo-a za kategoriju #
        #################################

        DB::table('SEO')->insert(
            [
                'machine_name'  =>  'category_' . $category_id,
                'url'           =>  'dvd-blueray-player/',
            ]
        );

        ###############################
        #       Unos atributa         #
        ###############################

        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_dvd_blue_player_proizvodja',
                'name_import'       =>  'Proizvođač',
                'label'             =>  'Proizvođač',
                'type'              =>  'checkbox',
                'order_category'    =>  1,
                'order_filter'      =>  1,
                'order_product'     =>  1,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_dvd_blue_player_tip',
                'name_import'       =>  'Tip',
                'label'             =>  'Tip',
                'type'              =>  'checkbox',
                'order_category'    =>  2,
                'order_filter'      =>  2,
                'order_product'     =>  2,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_dvd_blue_player_podr_forma',
                'name_import'       =>  'Podržani formati',
                'label'             =>  'Podržani formati',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  3,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_dvd_blue_player_mrezni_pri',
                'name_import'       =>  'Mrežni priključak',
                'label'             =>  'Mrežni priključak',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  3,
                'order_product'     =>  4,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_usb',
                'name_import'       =>  'USB priključak',
                'label'             =>  'USB priključak',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  5,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_daljinski',
                'name_import'       =>  'Daljinski',
                'label'             =>  'Daljinski',
                'type'              =>  'checkbox',
                'order_category'    =>  3,
                'order_filter'      =>  null,
                'order_product'     =>  6,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_dvd_blue_player_zvucni_sis',
                'name_import'       =>  'Zvučni sistem',
                'label'             =>  'Zvučni sistem',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  7,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_garantni_rok',
                'name_import'       =>  'Garantni rok',
                'label'             =>  'Garantni rok',
                'type'              =>  'checkbox',
                'order_category'    =>  4,
                'order_filter'      =>  null,
                'order_product'     =>  9,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_boja',
                'name_import'       =>  'Boja',
                'label'             =>  'Boja',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  8,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_dodatne_opcije',
                'name_import'       =>  'Dodatne opcije',
                'label'             =>  'Dodatne opcije',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  null,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_link_proizvodjaca',
                'name_import'       =>  'Link proizvođača',
                'label'             =>  'Link proizvođača',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  10,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_youtube',
                'name_import'       =>  'Youtube code',
                'label'             =>  'Youtube code',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  11,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_ean_kod',
                'name_import'       =>  'EAN kod',
                'label'             =>  'EAN kod',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  null,
            ]
        );
        

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        $category = DB::table('Categories')
        ->where('name', '=', 'dvdblurayplayer')
        ->first();

        $category_id = $category->id;

        DB::table('Attributes')->where('category_id', '=', $category_id)->delete();
        DB::table('SEO')->where('machine_name', '=', 'category_' . $category_id)->delete();
        DB::table('Categories')->where('id', '=', $category_id)->delete();

    }
}
