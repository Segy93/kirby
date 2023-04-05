<?php
namespace App\Providers;

/**
 * Mapiranje zavisnosti polja za konfigurator
 */
class ConfiguratorMappingService extends BaseService {
    /**
     * Podaci koji se mapiraju za konfigurator
     * NAME_IMPORT [
     *   attribute => [
     *      type   => (direct, sum, contains, compare_less, compare_more),
     *      source => machine_name_attribute_source
     *      target => machine_name_attribute_target
     *    ]
     * ]
     *
     * @var array
     */
    private static $mapping_data = [
        'COOL' => [
            [
                'type'      => 'value-includes',
                'sources'   => ['field_kuleri_podrzani_cipseti'],
                // 'targets'   => ['field_maticna_cipset'],
                'targets'   => ['field_maticna_podnozje'],
            ],
        ],
        'procesori' => [
            [
                'type'      => 'direct',
                'sources'   => ['field_procesor_podnozje'],
                'targets'   => ['field_maticna_podnozje'],
            ],
            [
                'type'      => 'sum-<=',
                'sources'   => [
                    'field_procesori_maks_potrosnja',
                    'field_graficka_max_potrosnja',
                    'field_hard_diskovi_potrosnja'
                ],
                'targets'   => ['field_napajanja_snaga'],
            ],
        ],
        'graficke karte' => [
            [
                'type'      => 'sum-<=',
                'sources'   => [
                    'field_graficka_max_potrosnja',
                    'field_procesori_maks_potrosnja',
                    'field_hard_diskovi_potrosnja',
                ],
                'targets'   => ['field_napajanja_snaga'],
            ],
            // [
            //     'type'      => 'direct',
            //     'sources'   => ['field_graficka_bus_standard'],
            //     'targets'   => ['NEMA'],
            // ],
            // [
            //     'type'      => 'sum',
            //     'sources'   => ['field_hdmi_priklju_ak'],
            //     'targets'   => ['field_hdmi_prikljucak'],
            // ],
            // [
            //     'type'      => 'sum',
            //     'sources'   => ['field_hdmi_priklju_ak'],
            //     'targets'   => ['field_hdmi_prikljucak'],
            // ],
            // [
            //     'type'      => 'sum',
            //     'sources'   => ['field_display_port_izlaz'],
            //     'targets'   => ['field_display_port_izlaz'],
            // ],
            // [
            //     'type'      => 'sum',
            //     'sources'   => ['field_vga_prikljucak'],
            //     'targets'   => ['field_vga_prikljucak'],
            // ],
            // [
            //     'type'      => 'sum',
            //     'sources'   => ['field_dvi_prikljucak'],
            //     'targets'   => ['field_dvi_prikljucak'],
            // ],
        ],
        'CASE' => [
            [
                'type'      => 'value-includes',
                'sources'   => ['field_kucista_podrzan_format_mb'],
                'targets'   => ['field_maticna_dimenzije'],
            ],
            [
                'type'      => 'compare_less',
                'sources'   => ['field_kucista_broj_2_5_lezista'],
                'targets'   => [],
            ],
            [
                'type'      => 'sum->=',
                'sources'   => ['field_snaga_napajanja'],
                'targets'   => [
                    'field_graficka_max_potrosnja',
                    'field_procesori_maks_potrosnja',
                    'field_hard_diskovi_potrosnja'
                ],
            ],
        ],
        'Matične ploče' => [
            [
                'type'      => 'value-included',
                'sources'   => ['field_maticna_podnozje'],
                // 'sources'   => ['field_maticna_cipset'],
                'targets'   => ['field_kuleri_podrzani_cipseti'],
            ],
            [
                'type'      => 'direct',
                'sources'   => ['field_maticna_podrzana_memorija'],
                'targets'   => ['field_tip_memorije'],
            ],
            [
                'type'      => 'direct',
                'sources'   => ['field_maticna_podnozje'],
                'targets'   => ['field_procesor_podnozje'],
            ],
            [
                'type'      => 'count_ram',
                'sources'   => ['field_maticna_br_memor_slotova'],
                'targets'   => ['field_ram_pakovanje'],
            ],
            [
                'type'      => 'sum_ram_size',
                'sources'   => ['field_maticna_maks_kolicina_memo'],
                'targets'   => ['field_ram_kapacitet'],
            ],
            [
                'type'      => 'compare_less',
                'sources'   => ['field_maticna_podrzana_brz_memor'],
                'targets'   => ['field_ram_brzina'],
            ],
            [
                'type'      => 'count_hdd_ssd',
                'sources'   => ['field_maticna_sata'],
                'targets'   => [],
            ],
            [
                'type'      => 'value-included',
                'sources'   => ['field_maticna_dimenzije'],
                'targets'   => ['field_kucista_podrzan_format_mb'],
            ],
            // [
            //     'type'      => 'sum',
            //     'sources'   => ['field_hdmi_prikljucak'],
            //     'targets'   => ['field_hdmi_priklju_ak'],
            // ],
            // [
            //     'type'      => 'sum',
            //     'sources'   => ['field_display_port_izlaz'],
            //     'targets'   => ['field_display_port_izlaz'],
            // ],
        ],
        'ram' => [
            [
                'type'      => 'direct',
                'sources'   => ['field_tip_memorije'],
                'targets'   => ['field_maticna_podrzana_memorija'],
            ],
            [
                'type'      => 'compare_less',
                'sources'   => ['field_ram_brzina'],
                'targets'   => ['field_maticna_podrzana_brz_memor'],
            ],
            [
                'type'      => 'sum_ram_size',
                'sources'   => ['field_ram_kapacitet'],
                'targets'   => ['field_maticna_maks_kolicina_memo'],
            ],
            [
                'type'      => 'count_ram',
                'sources'   => ['field_ram_pakovanje'],
                'targets'   => ['field_maticna_br_memor_slotova'],
            ],
        ],
        'SSD' => [
            [
                'type'      => 'direct',
                'sources'   => ['field_ssd_diskovi_format'],
                'targets'   => ['NEMA'],
            ],
        ],
        'PSU' => [
            [
                'type'      => 'sum->=',
                'sources'   => ['field_napajanja_snaga'],
                'targets'   => [
                    'field_graficka_max_potrosnja',
                    'field_procesori_maks_potrosnja',
                    'field_hard_diskovi_potrosnja'
                ],
            ],
        ],
        'MON' => [
            // [
            //     'type'      => 'sum',
            //     'sources'   => ['field_hdmi_prikljucak'],
            //     'targets'   => ['field_hdmi_priklju_ak'],
            // ],
            // [
            //     'type'      => 'sum',
            //     'sources'   => ['field_display_port_izlaz'],
            //     'targets'   => ['field_display_port_izlaz'],
            // ],
            // [
            //     'type'      => 'sum',
            //     'sources'   => ['field_vga_prikljucak'],
            //     'targets'   => ['field_vga_prikljucak'],
            // ],
            // [
            //     'type'      => 'sum',
            //     'sources'   => ['field_dvi_prikljucak'],
            //     'targets'   => ['field_dvi_prikljucak'],
            // ],
        ],
    ];

    /**
     * Konfiguracija za mapiranje kategorije, radi provere kompatibilnosti
     *
     * @param   string      $name_import    Naziv kategorije
     * @return  array|null                  Konfirugacija ili null ukoliko ne postoji
     */
    public static function getMappingData(string $name_import): ?array {
        if (array_key_exists($name_import, self::$mapping_data) === false) {
            return null;
        }

        return self::$mapping_data[$name_import];
    }
}
