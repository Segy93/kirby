<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class maticneploceCategoryMapping extends Migration {
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
                'name'          =>  'Matične ploče',
                'name_import'   =>  'Matične ploče',
            ]
        );

        ##################################
        # Dohvatanje kreirane kategorije #
        ##################################
        $category = DB::table('Categories')
            ->where('name', '=', 'Matične ploče')
            ->first()
        ;

        $category_id = $category->id;

        #################################
        # Kreiranje seo-a za kategoriju #
        #################################

        DB::table('SEO')->insert(
            [
                'machine_name'  =>  'category_' . $category_id,
                'url'           =>  'maticne-ploce/',
            ]
        );

        ###############################
        #       Unos atributa         #
        ###############################

        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_bluetooth_vrsta',
                'name_import'       =>  'Bluetooth',
                'label'             =>  'Bluetooth',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  13,
                'order_product'     =>  17,
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
                'order_product'     =>  31,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_broj_ata_prikljucaka',
                'name_import'       =>  'Broj ATA priključaka',
                'label'             =>  'Broj ATA priključaka',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  null,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_broj_audio_prikljucaka‎',
                'name_import'       =>  'Broj audio priključaka',
                'label'             =>  'Broj audio priključaka',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  28,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_broj_msata_prikljucaka',
                'name_import'       =>  'Broj mSATA priključaka',
                'label'             =>  'Broj mSATA priključaka',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  null,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_maticna_br_memor_slotova',
                'name_import'       =>  'Broj memorijskih slotova',
                'label'             =>  'Broj memorijskih slotova',
                'type'              =>  'checkbox',
                'order_category'    =>  5,
                'order_filter'      =>  4,
                'order_product'     =>  4,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_maticna_broj_pci',
                'name_import'       =>  'Broj PCI',
                'label'             =>  'Broj PCI',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  10,
                'order_product'     =>  13,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_maticna_pci_express_x1',
                'name_import'       =>  'Broj PCI Expres x 1',
                'label'             =>  'Broj PCI Expres x 1',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  9,
                'order_product'     =>  12,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_maticna_pci_express_x4',
                'name_import'       =>  'Broj PCI Express x4',
                'label'             =>  'Broj PCI Express x4',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  8,
                'order_product'     =>  11,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_maticna_pci_express_x8',
                'name_import'       =>  'Broj PCI Express x8',
                'label'             =>  'Broj PCI Express x8',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  7,
                'order_product'     =>  10,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_maticna_pci_express_x16',
                'name_import'       =>  'Broj PCI Express x16',
                'label'             =>  'Broj PCI Express x16',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  6,
                'order_product'     =>  9,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_maticna_br_podrz_procesora',
                'name_import'       =>  'Broj podržanih procesora',
                'label'             =>  'Broj podržanih procesora',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  null,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_maticna_sata',
                'name_import'       =>  'Broj sat priključaka',
                'label'             =>  'Broj sat priključaka',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  27,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_broj_sata_express_prikljuc',
                'name_import'       =>  'Broj SATA Express priključaka',
                'label'             =>  'Broj SATA Express priključaka',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  null,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_broj_usb_prikljucaka',
                'name_import'       =>  'Broj USB priključaka',
                'label'             =>  'Broj USB priključaka',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  14,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_maticna_cipset',
                'name_import'       =>  'Čipset',
                'label'             =>  'Čipset',
                'type'              =>  'checkbox',
                'order_category'    =>  3,
                'order_filter'      =>  3,
                'order_product'     =>  3,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_maticna_com_port',
                'name_import'       =>  'COM port',
                'label'             =>  'COM port',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  22,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_maticna_dimenzije',
                'name_import'       =>  'Dimenzije : atx mini itd.',
                'label'             =>  'Dimenzije : atx mini itd.',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  29,
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
                'order_filter'      =>  17,
                'order_product'     =>  21,
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
                'machine_name'      =>  'field_dvi_prikljucak',
                'name_import'       =>  'DVI izlaz',
                'label'             =>  'DVI izlaz',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  15,
                'order_product'     =>  19,
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
                'machine_name'      =>  'field_maticna_esata',
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
                'machine_name'      =>  'field_maticna_firewire',
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
                'order_category'    =>  6,
                'order_filter'      =>  null,
                'order_product'     =>  32,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_hdmi_priklju_ak',
                'name_import'       =>  'HDMI izlaz',
                'label'             =>  'HDMI izlaz',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  16,
                'order_product'     =>  20,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_lan_vrsta',
                'name_import'       =>  'LAN',
                'label'             =>  'LAN',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  11,
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
                'order_product'     =>  33,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_maticna_maks_kolicina_memo',
                'name_import'       =>  'Maks.količina memorije u GB',
                'label'             =>  'Maks.količina memorije u GB',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  7,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_maticna_max_potrosnja',
                'name_import'       =>  'Maks. Potrošnja u W',
                'label'             =>  'Maks. Potrošnja u W',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  null,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_maticna_multi_gpu',
                'name_import'       =>  'Multi GPU podrška',
                'label'             =>  'Multi GPU podrška',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  18,
                'order_product'     =>  26,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_maticna_namena',
                'name_import'       =>  'Namena',
                'label'             =>  'Namena',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  null,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_maticna_serial_port',
                'name_import'       =>  'Paralel port',
                'label'             =>  'Paralel port',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  23,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_maticna_podnozje',
                'name_import'       =>  'Podnožje ( soket )',
                'label'             =>  'Podnožje ( soket )',
                'type'              =>  'checkbox',
                'order_category'    =>  2,
                'order_filter'      =>  2,
                'order_product'     =>  2,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_maticna_podrzana_brz_memor',
                'name_import'       =>  'Podržana brzina memorije do',
                'label'             =>  'Podržana brzina memorije do',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  8,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_maticna_podrzana_memorija',
                'name_import'       =>  'Podržana memorija:',
                'label'             =>  'Podržana memorija:',
                'type'              =>  'checkbox',
                'order_category'    =>  4,
                'order_filter'      =>  5,
                'order_product'     =>  5,
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
                'order_product'     =>  30,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_maticna_ploca_proizvodjac',
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
                'machine_name'      =>  'field_maticna_raid',
                'name_import'       =>  'RAID podrška',
                'label'             =>  'RAID podrška',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  25,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_maticna_rezim_rada_memorij',
                'name_import'       =>  'Režim rada memorije:',
                'label'             =>  'Režim rada memorije:',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  6,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_maticna_thunderbolt',
                'name_import'       =>  'THUNDERBOLT',
                'label'             =>  'THUNDERBOLT',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  24,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_maticne_ploce_ukup_br_sata',
                'name_import'       =>  'Broj SATA priključaka',
                'label'             =>  'Broj SATA priključaka',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  null,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_maticne_ploce_ukup_br_usb',
                'name_import'       =>  'UPARI2',
                'label'             =>  'UPARI2',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  null,
                'order_product'     =>  null,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_vga_prikljucak',
                'name_import'       =>  'VGA izlaz',
                'label'             =>  'VGA izlaz',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  14,
                'order_product'     =>  18,
            ]
        );
        
        DB::table('Attributes')->insert(
            [
                'category_id'       =>  $category_id,
                'machine_name'      =>  'field_wireless_vrsta',
                'name_import'       =>  'Wireless',
                'label'             =>  'Wireless',
                'type'              =>  'checkbox',
                'order_category'    =>  null,
                'order_filter'      =>  12,
                'order_product'     =>  16,
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
        

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        $category = DB::table('Categories')
        ->where('name', '=', 'maticneploce')
        ->first();

        $category_id = $category->id;

        DB::table('Attributes')->where('category_id', '=', $category_id)->delete();
        DB::table('SEO')->where('machine_name', '=', 'category_' . $category_id)->delete();
        DB::table('Categories')->where('id', '=', $category_id)->delete();

    }
}
