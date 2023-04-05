<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class nasirackuređajiCategoryMapping extends Migration {
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
                'name'          =>  'NAS i rack uređaji',
                'name_import'   =>  'HD RACK',
            ]
        );

        ##################################
        # Dohvatanje kreirane kategorije #
        ##################################
        $category = DB::table('Categories')
            ->where('name', '=', 'NAS i rack uređaji')
            ->first()
        ;

        $category_id = $category->id;

        #################################
        # Kreiranje seo-a za kategoriju #
        #################################

        DB::table('SEO')->insert(
            [
                'machine_name'  =>  'category_' . $category_id,
                'url'           =>  'nas-rack-uredjaji/',
            ]
        );

        ###############################
        #       Unos atributa         #
        ###############################

        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_nas_rack_proizvodjac',
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
                'machine_name'      =>  'field_nas_rack_tip',
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
                'machine_name'      =>  'field_nas_rack_br_lezista_za_dis',
                'name_import'       =>  'Broj ležišta za diskove',
                'label'             =>  'Broj ležišta za diskove',
                'type'              =>  'checkbox',
                'order_category'    =>  3,
                'order_filter'      =>  3,
                'order_product'     =>  3,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_nas_rack_max_trans_podatak‎',
                'name_import'       =>  'Max.Transfer podataka',
                'label'             =>  'Max.Transfer podataka',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  4,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_nas_rack_max_kapacitet',
                'name_import'       =>  'Maksimalan kapacitet',
                'label'             =>  'Maksimalan kapacitet',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  5,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_nas_rack_raid_opcije',
                'name_import'       =>  'Raid opcije',
                'label'             =>  'Raid opcije',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  6,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_nas_rack_tip_procesora',
                'name_import'       =>  'Tip procesora',
                'label'             =>  'Tip procesora',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  null,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_nas_rack_nacin_prikljuciva',
                'name_import'       =>  'Način priključivanja',
                'label'             =>  'Način priključivanja',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  7,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_nas_rack_brzina_mreze',
                'name_import'       =>  'Brzina mreže',
                'label'             =>  'Brzina mreže',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  8,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_nas_rack_br_mreznih_priklj',
                'name_import'       =>  'Broj mrežnih priključaka',
                'label'             =>  'Broj mrežnih priključaka',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  9,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_displej',
                'name_import'       =>  'Displej',
                'label'             =>  'Displej',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  10,
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
                'order_product'     =>  12,
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
                'order_product'     =>  11,
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
                'order_product'     =>  13,
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
                'order_product'     =>  14,
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
        ->where('name', '=', 'nasirackuređaji')
        ->first();

        $category_id = $category->id;

        DB::table('Attributes')->where('category_id', '=', $category_id)->delete();
        DB::table('SEO')->where('machine_name', '=', 'category_' . $category_id)->delete();
        DB::table('Categories')->where('id', '=', $category_id)->delete();

    }
}
