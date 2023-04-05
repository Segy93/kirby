<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class notebookpunjaciCategoryMapping extends Migration {
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
                'name'          =>  'Notebook punjači',
                'name_import'   =>  'PUNJACI',
            ]
        );

        ##################################
        # Dohvatanje kreirane kategorije #
        ##################################
        $category = DB::table('Categories')
            ->where('name', '=', 'Notebook punjači')
            ->first()
        ;

        $category_id = $category->id;

        #################################
        # Kreiranje seo-a za kategoriju #
        #################################

        DB::table('SEO')->insert(
            [
                'machine_name'  =>  'category_' . $category_id,
                'url'           =>  'notebook-punjaci/',
            ]
        );

        ###############################
        #       Unos atributa         #
        ###############################

        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_lap_punjaci_proizvodjac',
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
                'machine_name'      =>  'field_punjaci_preporuceno_za_mod',
                'name_import'       =>  'Preporučeno za modele',
                'label'             =>  'Preporučeno za modele',
                'type'              =>  'checkbox',
                'order_category'    =>  2,
                'order_filter'      =>  2,
                'order_product'     =>  2,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_punjaci_podesavanje_izlazn',
                'name_import'       =>  'Podešavanje izlazne snage',
                'label'             =>  'Podešavanje izlazne snage',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  3,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_punjaci_maksimalna_snaga',
                'name_import'       =>  'Maksimalna snaga',
                'label'             =>  'Maksimalna snaga',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  4,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_garantni_rok',
                'name_import'       =>  'Garantni rok',
                'label'             =>  'Garantni rok',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  6,
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
                'order_product'     =>  5,
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
                'order_product'     =>  7,
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
                'order_product'     =>  8,
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
        ->where('name', '=', 'notebookpunjaci')
        ->first();

        $category_id = $category->id;

        DB::table('Attributes')->where('category_id', '=', $category_id)->delete();
        DB::table('SEO')->where('machine_name', '=', 'category_' . $category_id)->delete();
        DB::table('Categories')->where('id', '=', $category_id)->delete();

    }
}
