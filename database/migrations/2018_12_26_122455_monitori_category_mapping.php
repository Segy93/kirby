<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class monitoriCategoryMapping extends Migration {
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
                'name'          =>  'Monitori',
                'name_import'   =>  'MON',
            ]
        );

        ##################################
        # Dohvatanje kreirane kategorije #
        ##################################
        $category = DB::table('Categories')
            ->where('name', '=', 'Monitori')
            ->first()
        ;

        $category_id = $category->id;

        #################################
        # Kreiranje seo-a za kategoriju #
        #################################

        DB::table('SEO')->insert(
            [
                'machine_name'  =>  'category_' . $category_id,
                'url'           =>  'monitori/',
            ]
        );

        ###############################
        #       Unos atributa         #
        ###############################

        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_monitor_odziv',
                'name_import'       =>  'Brzina odziva',
                'label'             =>  'Brzina odziva',
                'type'              =>  'checkbox',
                'order_category'    =>  5,
                'order_filter'      =>  5,
                'order_product'     =>  6,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_monitori_dijagonala',
                'name_import'       =>  'Dijagonala ekrana',
                'label'             =>  'Dijagonala ekrana',
                'type'              =>  'checkbox',
                'order_category'    =>  2,
                'order_filter'      =>  2,
                'order_product'     =>  2,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_display_port_izlaz',
                'name_import'       =>  'Displej port',
                'label'             =>  'Displej port',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  9,
                'order_product'     =>  10,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_dvi_prikljucak',
                'name_import'       =>  'DVI priključak',
                'label'             =>  'DVI priključak',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  7,
                'order_product'     =>  8,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_monitori_kontrast',
                'name_import'       =>  'Kontrast',
                'label'             =>  'Kontrast',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  7,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_monitori_proizvodjac',
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
                'machine_name'      =>  'field_monitori_rezolucija',
                'name_import'       =>  'Rezolucija ekrana',
                'label'             =>  'Rezolucija ekrana',
                'type'              =>  'checkbox',
                'order_category'    =>  3,
                'order_filter'      =>  3,
                'order_product'     =>  3,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_monitori_tip_ekrana',
                'name_import'       =>  'Tip ekrana',
                'label'             =>  'Tip ekrana',
                'type'              =>  'checkbox',
                'order_category'    =>  4,
                'order_filter'      =>  4,
                'order_product'     =>  4,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_vga_prikljucak',
                'name_import'       =>  'VGA priključak',
                'label'             =>  'VGA priključak',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  8,
                'order_product'     =>  9,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_hdmi_prikljucak',
                'name_import'       =>  'HDMI priključak',
                'label'             =>  'HDMI priključak',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  6,
                'order_product'     =>  11,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_broj_usb_prikljucaka',
                'name_import'       =>  'USB priključak',
                'label'             =>  'USB priključak',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  12,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_zvucnici',
                'name_import'       =>  'Zvučnici',
                'label'             =>  'Zvučnici',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  15,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_monitori_audio_input',
                'name_import'       =>  'Audio ulaz',
                'label'             =>  'Audio ulaz',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  13,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_monitori_priklj_slusalice',
                'name_import'       =>  'Priključak za slušalice',
                'label'             =>  'Priključak za slušalice',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  14,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_monitori_ugao_gledanja',
                'name_import'       =>  'Ugao gledanja',
                'label'             =>  'Ugao gledanja',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  16,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_monitori_osvetljenost',
                'name_import'       =>  'Osvetljenost',
                'label'             =>  'Osvetljenost',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  10,
                'order_product'     =>  17,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_preporuceno',
                'name_import'       =>  'Preporučeno za',
                'label'             =>  'Preporučeno za',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  19,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_monitori_dimenz_sa_postolj',
                'name_import'       =>  'Dimenzije sa postoljem',
                'label'             =>  'Dimenzije sa postoljem',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  21,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_garantni_rok',
                'name_import'       =>  'Garantni rok',
                'label'             =>  'Garantni rok',
                'type'              =>  'checkbox',
                'order_category'    =>  6,
                'order_filter'      =>  null,
                'order_product'     =>  22,
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
                'order_product'     =>  20,
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
                'order_product'     =>  23,
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
                'order_product'     =>  24,
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
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_monitori_refresh_rate',
                'name_import'       =>  'Refresh rate',
                'label'             =>  'Refresh rate',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  5,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_monitori_dodatne_mogucnost',
                'name_import'       =>  'Dodatne mogućnosti',
                'label'             =>  'Dodatne mogućnosti',
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
        ->where('name', '=', 'monitori')
        ->first();

        $category_id = $category->id;

        DB::table('Attributes')->where('category_id', '=', $category_id)->delete();
        DB::table('SEO')->where('machine_name', '=', 'category_' . $category_id)->delete();
        DB::table('Categories')->where('id', '=', $category_id)->delete();

    }
}
