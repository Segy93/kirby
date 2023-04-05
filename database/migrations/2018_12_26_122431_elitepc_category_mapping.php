<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class elitepcCategoryMapping extends Migration {
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
                'name'          =>  'Elite PC',
                'name_import'   =>  'Elite PC',
            ]
        );

        ##################################
        # Dohvatanje kreirane kategorije #
        ##################################
        $category = DB::table('Categories')
            ->where('name', '=', 'Elite PC')
            ->first()
        ;

        $category_id = $category->id;

        #################################
        # Kreiranje seo-a za kategoriju #
        #################################

        DB::table('SEO')->insert(
            [
                'machine_name'  =>  'category_' . $category_id,
                'url'           =>  'elite-pc/',
            ]
        );

        ###############################
        #       Unos atributa         #
        ###############################

        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_boja',
                'name_import'       =>  'Boja',
                'label'             =>  'Boja',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  31,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_elite_brzina_memorije',
                'name_import'       =>  'Brzina memorije',
                'label'             =>  'Brzina memorije',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  16,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_citac_kartica',
                'name_import'       =>  'Čitač kartica',
                'label'             =>  'Čitač kartica',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  28,
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
                'machine_name'      =>  'field_elite_pc_tip',
                'name_import'       =>  'elite_pc',
                'label'             =>  'elite_pc',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  null,
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
                'order_product'     =>  32,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_elite_graficka_karta',
                'name_import'       =>  'Grafička karta 1',
                'label'             =>  'Grafička karta 1',
                'type'              =>  'checkbox',
                'order_category'    =>  4,
                'order_filter'      =>  8,
                'order_product'     =>  11,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_elite_graficka_karta_2',
                'name_import'       =>  'Grafička karta 2',
                'label'             =>  'Grafička karta 2',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  12,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_ram_kapacitet',
                'name_import'       =>  'Količina memorije',
                'label'             =>  'Količina memorije',
                'type'              =>  'checkbox',
                'order_category'    =>  5,
                'order_filter'      =>  7,
                'order_product'     =>  14,
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
                'order_product'     =>  33,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_elite_model_maticne_ploce',
                'name_import'       =>  'Model matične ploče',
                'label'             =>  'Model matične ploče',
                'type'              =>  'checkbox',
                'order_category'    =>  3,
                'order_filter'      =>  4,
                'order_product'     =>  9,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_elite_model_memorije',
                'name_import'       =>  'Model memorije',
                'label'             =>  'Model memorije',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  13,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_elite_model_hard_diska',
                'name_import'       =>  'Model hard diska 1',
                'label'             =>  'Model hard diska 1',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  17,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_elite_kuciste_model',
                'name_import'       =>  'Model kućišta',
                'label'             =>  'Model kućišta',
                'type'              =>  'checkbox',
                'order_category'    =>  6,
                'order_filter'      =>  9,
                'order_product'     =>  24,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_elite_model_hard_diska_2',
                'name_import'       =>  'Model hard diska 2',
                'label'             =>  'Model hard diska 2',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  18,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_elite_opticki_uredjaj',
                'name_import'       =>  'Model optičkog uređaja',
                'label'             =>  'Model optičkog uređaja',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  26,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_elite_model_procesora',
                'name_import'       =>  'Model procesora 1',
                'label'             =>  'Model procesora 1',
                'type'              =>  'checkbox',
                'order_category'    =>  2,
                'order_filter'      =>  null,
                'order_product'     =>  5,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_elite_model_procesora_2',
                'name_import'       =>  'Model procesora 2',
                'label'             =>  'Model procesora 2',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  6,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_elite_model_ssd_a',
                'name_import'       =>  'Model SSD-a',
                'label'             =>  'Model SSD-a',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  21,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_elite_monitor',
                'name_import'       =>  'Monitor',
                'label'             =>  'Monitor',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  2,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_preporuceno',
                'name_import'       =>  'Namenjeno za',
                'label'             =>  'Namenjeno za',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  29,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_elite_napajanje',
                'name_import'       =>  'Naponska jedinica',
                'label'             =>  'Naponska jedinica',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  10,
                'order_product'     =>  25,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_operativni_sistem',
                'name_import'       =>  'Operativni sistem (licenca)',
                'label'             =>  'Operativni sistem (licenca)',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  30,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_elite_oznaka_procesora',
                'name_import'       =>  'Oznaka procesora',
                'label'             =>  'Oznaka procesora',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  3,
                'order_product'     =>  3,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_elite_procesorski_hladnjak',
                'name_import'       =>  'Procesorski hladnjak',
                'label'             =>  'Procesorski hladnjak',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  7,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_elite_pc_proizvodjac',
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
                'machine_name'      =>  'field_elite_proizvodjac_gpu',
                'name_import'       =>  'Proizvođač GPU-a',
                'label'             =>  'Proizvođač GPU-a',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  null,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_elite_proizvodjac_grafike',
                'name_import'       =>  'Proizvođač grafičke karte',
                'label'             =>  'Proizvođač grafičke karte',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  10,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_elite_proizvodjac_kucista',
                'name_import'       =>  'Proizvođač kućišta',
                'label'             =>  'Proizvođač kućišta',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  23,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_maticna_ploca_proizvodjac',
                'name_import'       =>  'Proizvođač matične ploče',
                'label'             =>  'Proizvođač matične ploče',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  8,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_proizvodjac',
                'name_import'       =>  'Proizvođač procesora 1',
                'label'             =>  'Proizvođač procesora',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  2,
                'order_product'     =>  4,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_sertifikat',
                'name_import'       =>  'Sertifikat',
                'label'             =>  'Sertifikat',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  null,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_elite_tip_memorije',
                'name_import'       =>  'Tip memorije',
                'label'             =>  'Tip memorije',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  15,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_elite_tip_os',
                'name_import'       =>  'Tip operativnog sistema',
                'label'             =>  'Tip operativnog sistema',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  null,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_hard_diskovi_velicina',
                'name_import'       =>  'Veličina Hard disk 1',
                'label'             =>  'Veličina Hard disk 1',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  5,
                'order_product'     =>  18,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_velicina_hard_disk_2',
                'name_import'       =>  'Veličina Hard disk 2',
                'label'             =>  'Veličina Hard disk 2',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  20,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_velicina_ssd_a‎ ',
                'name_import'       =>  'Veličina SSD-a',
                'label'             =>  'Veličina SSD-a',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  6,
                'order_product'     =>  22,
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
                'order_product'     =>  34,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_elite_zvucna_karta',
                'name_import'       =>  'Zvučna karta',
                'label'             =>  'Zvučna karta',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  27,
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
        ->where('name', '=', 'elitepc')
        ->first();

        $category_id = $category->id;

        DB::table('Attributes')->where('category_id', '=', $category_id)->delete();
        DB::table('SEO')->where('machine_name', '=', 'category_' . $category_id)->delete();
        DB::table('Categories')->where('id', '=', $category_id)->delete();

    }
}
