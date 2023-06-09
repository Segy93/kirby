<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class laptopoviCategoryMapping extends Migration {
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
                'name'          =>  'Laptopovi',
                'name_import'   =>  'NBOOK',
            ]
        );

        ##################################
        # Dohvatanje kreirane kategorije #
        ##################################
        $category = DB::table('Categories')
            ->where('name', '=', 'Laptopovi')
            ->first()
        ;

        $category_id = $category->id;

        #################################
        # Kreiranje seo-a za kategoriju #
        #################################

        DB::table('SEO')->insert(
            [
                'machine_name'  =>  'category_' . $category_id,
                'url'           =>  'laptopovi/',
            ]
        );

        ###############################
        #       Unos atributa         #
        ###############################

        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_3d_ekran',
                'name_import'       =>  '3D Ekran',
                'label'             =>  '3D Ekran',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  null,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_laptopovi_baterija',
                'name_import'       =>  'Baterija',
                'label'             =>  'Baterija',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  19,
                'order_product'     =>  25,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_bluetooth',
                'name_import'       =>  'Bluetooth',
                'label'             =>  'Bluetooth',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  26,
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
                'order_product'     =>  47,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_procesor_broj_jezgara',
                'name_import'       =>  'Broj procesorskih jezgara',
                'label'             =>  'Broj procesorskih jezgara',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  8,
                'order_product'     =>  11,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_broj_usb_2_0',
                'name_import'       =>  'Broj USB 2.0',
                'label'             =>  'Broj USB 2.0',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  30,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_broj_usb_3_0',
                'name_import'       =>  'Broj USB 3.0',
                'label'             =>  'Broj USB 3.0',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  16,
                'order_product'     =>  31,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_laptopovi_brzina_memorije',
                'name_import'       =>  'Brzina memorije',
                'label'             =>  'Brzina memorije',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  11,
                'order_product'     =>  13,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_laptopovi_brzina_obrataja_',
                'name_import'       =>  'Brzina obrataja diska',
                'label'             =>  'Brzina obrataja diska',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  19,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_citac_kartica',
                'name_import'       =>  'Card reader',
                'label'             =>  'Card reader',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  28,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_maticna_cipset',
                'name_import'       =>  'Čipset',
                'label'             =>  'Čipset',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  20,
                'order_product'     =>  9,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_display_port_izlaz',
                'name_import'       =>  'Display port',
                'label'             =>  'Display port',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  25,
                'order_product'     =>  36,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_tastatura_dodatni_opis‎',
                'name_import'       =>  'Dodatan opis tastature',
                'label'             =>  'Dodatan opis tastature',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  38,
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
                'machine_name'      =>  'field_laptopovi_dodatni_detalji',
                'name_import'       =>  'Dodatni detalji',
                'label'             =>  'Dodatni detalji',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  null,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_dvi_prikljucak',
                'name_import'       =>  'DVI',
                'label'             =>  'DVI',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  23,
                'order_product'     =>  35,
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
                'machine_name'      =>  'field_esata',
                'name_import'       =>  'eSata',
                'label'             =>  'eSata',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  null,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_firewire',
                'name_import'       =>  'Firewire',
                'label'             =>  'Firewire',
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
                'order_filter'      =>  24,
                'order_product'     =>  48,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_laptopovi_hard_disk_2',
                'name_import'       =>  'Hard disk 2',
                'label'             =>  'Hard disk 2',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  14,
                'order_product'     =>  20,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_hdmi_priklju_ak',
                'name_import'       =>  'HDMI',
                'label'             =>  'HDMI',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  21,
                'order_product'     =>  33,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_ram_kapacitet',
                'name_import'       =>  'Kapacitet memorije',
                'label'             =>  'Kapacitet memorije',
                'type'              =>  'checkbox',
                'order_category'    =>  4,
                'order_filter'      =>  10,
                'order_product'     =>  15,
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
                'order_product'     =>  49,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_materijal',
                'name_import'       =>  'Materijal',
                'label'             =>  'Materijal',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  44,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_graficki_cip',
                'name_import'       =>  'Model Grafičke karte',
                'label'             =>  'Model Grafičke karte',
                'type'              =>  'checkbox',
                'order_category'    =>  6,
                'order_filter'      =>  15,
                'order_product'     =>  23,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_laptopovi_mogucnost_prosir',
                'name_import'       =>  'Mogucnost proširenja',
                'label'             =>  'Mogucnost proširenja',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  16,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_laptopovi_mrezna_kartica',
                'name_import'       =>  'Mrežna kartica',
                'label'             =>  'Mrežna kartica',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  32,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_laptopovi_os',
                'name_import'       =>  'Operativni sistem',
                'label'             =>  'Operativni sistem',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  43,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_laptopovi_opticki_uredjaj',
                'name_import'       =>  'Optički uredjaj',
                'label'             =>  'Optički uredjaj',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  21,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_laptopovi_oznaka_procesora',
                'name_import'       =>  'Oznaka procesora',
                'label'             =>  'Oznaka procesora',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  7,
                'order_product'     =>  8,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_preporuceno',
                'name_import'       =>  'Preporučena za',
                'label'             =>  'Preporučena za',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  51,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_prikljucak_za_mikrofon',
                'name_import'       =>  'Priključak za mikrofon',
                'label'             =>  'Priključak za mikrofon',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  41,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_prikljucak_za_slusalice',
                'name_import'       =>  'Priključak za slusalice',
                'label'             =>  'Priključak za slusalice',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  40,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_laptopovi_procesor',
                'name_import'       =>  'Procesor',
                'label'             =>  'Procesor',
                'type'              =>  'checkbox',
                'order_category'    =>  3,
                'order_filter'      =>  9,
                'order_product'     =>  10,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_laptopovi_proizvodjac',
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
                'machine_name'      =>  'field_laptopovi_proizvodjac_proc',
                'name_import'       =>  'Proizvođač procesora',
                'label'             =>  'Proizvođač procesora',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  6,
                'order_product'     =>  7,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_procesor_frekvencija',
                'name_import'       =>  'Radni takt procesora',
                'label'             =>  'Radni takt procesora',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  12,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_laptopovi_rezolucija_ekran',
                'name_import'       =>  'Rezolucija ekrana',
                'label'             =>  'Rezolucija ekrana',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  3,
                'order_product'     =>  3,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_serial_port',
                'name_import'       =>  'Serial port',
                'label'             =>  'Serial port',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  null,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_spdif',
                'name_import'       =>  'SPDIF',
                'label'             =>  'SPDIF',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  null,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_tastatura',
                'name_import'       =>  'Tastatura',
                'label'             =>  'Tastatura',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  37,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_thunderbolt‎',
                'name_import'       =>  'THUNDERBOLT',
                'label'             =>  'THUNDERBOLT',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  null,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_laptopovi_tip_ekrana',
                'name_import'       =>  'Tip ekrana:',
                'label'             =>  'Tip ekrana:',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  5,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_laptopovi_tip_graficke_kar',
                'name_import'       =>  'Tip grafičke karte',
                'label'             =>  'Tip grafičke karte',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  22,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_tastatura_tip_karaktera‎',
                'name_import'       =>  'Tip karaktera',
                'label'             =>  'Tip karaktera',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  39,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_laptopovi_tip',
                'name_import'       =>  'Tip laptopa',
                'label'             =>  'Tip laptopa',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  52,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_laptopovi_tip_memorije',
                'name_import'       =>  'Tip memorije',
                'label'             =>  'Tip memorije',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  14,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_laptopovi_tip_os',
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
                'machine_name'      =>  'field_touchscreen',
                'name_import'       =>  'Touch screan',
                'label'             =>  'Touchscreen',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  5,
                'order_product'     =>  6,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_laptopovi_ukupan_broj_usb',
                'name_import'       =>  'Ukupan broj USB-ova',
                'label'             =>  'Ukupan broj USB-ova',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  17,
                'order_product'     =>  29,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_laptopovi_velicina_ekrana',
                'name_import'       =>  'Veličina ekrana',
                'label'             =>  'Veličina ekrana',
                'type'              =>  'checkbox',
                'order_category'    =>  2,
                'order_filter'      =>  2,
                'order_product'     =>  2,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_hard_diskovi_velicina',
                'name_import'       =>  'Veličina hardiska',
                'label'             =>  'Veličina hardiska',
                'type'              =>  'checkbox',
                'order_category'    =>  5,
                'order_filter'      =>  12,
                'order_product'     =>  17,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_vga_prikljucak',
                'name_import'       =>  'VGA',
                'label'             =>  'VGA',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  22,
                'order_product'     =>  34,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_laptopovi_vrsta_ekrana',
                'name_import'       =>  'Vrsta ekrana',
                'label'             =>  'Vrsta ekrana',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  4,
                'order_product'     =>  4,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_laptopovi_vrsta_hard_diska',
                'name_import'       =>  'Vrsta hard diska',
                'label'             =>  'Vrsta hard diska',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  13,
                'order_product'     =>  18,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_laptopovi_web_kamera',
                'name_import'       =>  'Web kamera',
                'label'             =>  'Web kamera',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  18,
                'order_product'     =>  24,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_wireless',
                'name_import'       =>  'Wireless',
                'label'             =>  'Wireless',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  27,
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
                'order_product'     =>  50,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_laptopovi_zvucnici',
                'name_import'       =>  'Zvučnici',
                'label'             =>  'Zvučnici',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  51,
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
        ->where('name', '=', 'laptopovi')
        ->first();

        $category_id = $category->id;

        DB::table('Attributes')->where('category_id', '=', $category_id)->delete();
        DB::table('SEO')->where('machine_name', '=', 'category_' . $category_id)->delete();
        DB::table('Categories')->where('id', '=', $category_id)->delete();

    }
}
