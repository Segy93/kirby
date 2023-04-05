<?php

namespace App\Providers;

use App\Providers\LogService;
use App\Providers\ProductService;
use Monolog\Logger;

/**
 * Konfigurator, osnovni servis
 */
class ConfiguratorService extends BaseService {
    private static $service_name = 'ConfiguratorService';
    private static $url = [];
    private static $sum_product_value = 0;
    private static $attribute_label = null;
    private static $attribute_value = null;
    //field_graficka_max_potrosnja
    //field_hard_diskovi_potrosnja
    //field_maticna_max_potrosnja
    //field_procesori_maks_potrosnja
    //field_ram_potrosnja
    /**
     * Direktno poređenje,
     * za slučajeve kad atributi moraju da imaju istu vrednost
     *
     * @param   integer     $product_id     ID proizvoda koji se dodaje
     * @param   int[]       $configuration  Niz sa ID-ovima proizvoda koji su već u konfiguraciji
     * @param   array       $field_mapping  Informacije o tome koje polje se s kojim uparuje
     * @param   string      $type           'equals', 'includes' 'included'
     * @return  boolean                     Da li je nova komponenta kompatibilna
     */
    private static function isCompatibleDirect(
        int $product_id,
        array $configuration,
        array $field_mapping,
        string $type
    ): bool {
        $logger = LogService::initLoggerConfigurator();

        /** @var string[] */
        $sources = $field_mapping['sources'];
        /** @var string[] */
        $targets = $field_mapping['targets'];

        foreach ($sources as $source) {
            $value_source = ProductService::getAttributeValueByMachineName(
                $product_id,
                $source
            );

            // Nije pronađen atribut,
            // ne možemo da budemo sigurni da li je kompatibilan,
            // pa pretpostavljamo da jeste
            if ($value_source === null) {
                $logger->debug('Nije pronađen izvorni atribut: "' . $source . '"');
                continue;
            }

            // Za svaku komponentu u konfiguraciji,
            // Proveravamo potrebne atribute
            foreach ($configuration as $target_id) {
                foreach ($targets as $target) {
                    $value_target = ProductService::getAttributeValueByMachineName(
                        $target_id,
                        $target
                    );

                    if ($value_target === null) {
                        $logger->debug('Nije pronađen ciljani atribut: "' . $target . '"');
                        continue;
                    }

                    $logger->debug(
                        'Atribut postojećeg proizvoda: "' . $target . '": "' . $value_target . '"'
                    );
                    $logger->debug(
                        'Atribut novog proizvoda: "' . $source . '": "' . $value_source . '"'
                    );

                    $compatible = true;
                    if ($type === 'equals') {
                        $compatible = $value_source === $value_target;
                    } elseif ($type === 'included') {
                        $compatible = strpos($value_target, $value_source) !== false;
                    } elseif ($type === 'includes') {
                        $compatible = strpos($value_source, $value_target) !== false;
                    } else {
                        $logger->warn('Nepoznat tip poređenja: "' . $type . '"');
                    }

                    if ($compatible === false) {
                        $logger->info(
                            'Nekompatibilni atributi: "' . $value_source . '" i "' . $value_target . '"'
                        );
                        return false;
                    }
                }
            }
        }

        return true;
    }

    /**
     * Provera kompatibilnosti za slučaj kada treba sabrati vrednosti polja
     * (na primer, broj HDMI priključaka na monitoru)
     *
     * @param   integer     $product_id     ID proizvoda koji se dodaje
     * @param   int[]       $configuration  Niz sa ID-ovima proizvoda koji su već u konfiguraciji
     * @param   array       $field_mapping  Informacije o tome koje polje se s kojim uparuje
     * @param   string      $direction      Da li da proveri '<=' ili '>=
     * @return  boolean                     Da li je polje kompatibilno
     */
    private static function isCompatibleSum(
        int $product_id,
        array $configuration,
        array $field_mapping,
        string $direction
    ): bool {
        $logger = LogService::initLoggerConfigurator();

        /** @var string[] */
        $sources = $field_mapping['sources'];
        /** @var string[] */
        $targets = $field_mapping['targets'];

        $sum_sources = 0;
        foreach ($sources as $source) {
            $value_source = ProductService::getAttributeValueByMachineName(
                $product_id,
                $source
            );

            if ($value_source === null) {
                continue;
            }

            if ($value_source === 'Bez napajanja') {
                return true;
            }

            $logger->debug('Vrednost source: "' . $value_source . '"');
            $sum_sources += intval($value_source);
        }

        $sum_targets = 0;
        $has_target = false;
        foreach ($targets as $target) {
            foreach ($configuration as $target_id) {
                $value_target = ProductService::getAttributeValueByMachineName(
                    $target_id,
                    $target
                );

                if ($value_target === null) {
                    continue;
                }

                if ($value_target === 'Bez napajanja') {
                    return true;
                }

                $has_target = true;
                $logger->debug('Vrednost target: "' . $value_target . '"');
                $sum_targets += intval($value_target);
            }
        }

        $logger->debug('Zbir: ' . $sum_sources . ' ' . $direction . ' ' . $sum_targets);

        if ($has_target === false) {
            return true;
        } elseif ($direction === '<=') {
            return $sum_sources <= $sum_targets;
        }

        return $sum_sources >= $sum_targets;
    }

    /**
     * Provera kompatibilnosti pojedinačnog polja
     *
     * @param   integer     $product_id     ID proizvoda koji se dodaje
     * @param   int[]       $configuration  Niz sa ID-ovima proizvoda koji su već u konfiguraciji
     * @param   array       $field_mapping  Informacije o tome koje polje se s kojim uparuje
     * @return  boolean                     Da li je polje kompatibilno
     */
    private static function isCompatibleField(
        int $product_id,
        array $configuration,
        array $field_mapping
    ): bool {
        $logger = LogService::initLoggerConfigurator();

        $type = $field_mapping['type'];
        $logger->debug('Tip: "' . $type . '"');
        if ($type === 'direct') {
            return self::isCompatibleDirect($product_id, $configuration, $field_mapping, 'equals');
        } elseif ($type === 'sum-<=') {
            return self::isCompatibleSum($product_id, $configuration, $field_mapping, '<=');
        } elseif ($type === 'sum->=') {
            return self::isCompatibleSum($product_id, $configuration, $field_mapping, '>=');
        } elseif ($type === 'value-included') {
            return self::isCompatibleDirect($product_id, $configuration, $field_mapping, 'included');
        } elseif ($type === 'value-includes') {
            return self::isCompatibleDirect($product_id, $configuration, $field_mapping, 'includes');
        }

        $logger->warning('Nepoznat tip mapiranja kompatibilnosti: "' . $type . '"');

        return true;
    }

    /**
     * Provera kompatibilnosti nove komponente u odnosu na postojecu konfiguraciju
     *
     * @param   integer     $product_id     ID proizvoda koji se dodaje
     * @param   int[]       $configuration  Niz sa ID-ovima proizvoda koji su već u konfiguraciji
     * @return  boolean                     Da li je nova komponenta kompatibilna
     */
    public static function isCompatible(int $product_id, array $configuration): bool {
        $logger = LogService::initLoggerConfigurator();

        if (ConfigService::getLoggerLevel() === Logger::DEBUG) {
            $logger->debug('Provera kompatibilnosti');
            foreach ($configuration as $component_id) {
                $component = ProductService::getProductById($component_id);

                $logger->debug('Komponenta: "' . $component->name . '"');
                $logger->debug('Iz kategorije: "' . $component->category->name_import . '"');
            }
        }

        $product = ProductService::getProductById($product_id);
        $name_import = $product->category->name_import;

        $logger->debug('Dodavanje proizvoda: "' . $product->name . '"');
        $logger->debug('Za kategoriju: "' . $name_import . '"');

        // Dodajemo prvu komponentu, nema s čime da je nekompatibilna
        if (count($configuration) === 0) {
            $logger->debug('Prazna konfiguracija');
            return true;
        }

        $category_mapping = ConfiguratorMappingService::getMappingData($name_import);
        // Ne postoji konfiguracija za kategoriju novog proizvoda

        // Pretpostavljamo da je kompatibilan sa ostatkom konfiguracije
        if ($category_mapping === null) {
            $logger->warning('Ne postoji mapiranje za kategoriju: ' . $name_import);
            return true;
        }

        foreach ($category_mapping as $field_mapping) {
            $is_compatible = self::isCompatibleField($product_id, $configuration, $field_mapping);

            // Ako je kompatibilno, nastavimo sa proverama,
            // ako nije, prekidamo odmah
            if ($is_compatible === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Dodavanje filtera u url na osnovu proizvoda u konfiguratoru
     *
     * @param int    $category_id            Id kategorije
     * @param array  $url                    Url
     * @param string $configuration_name     Naziv konfiguracije
     * @return array
     */
    public static function changeUrlFilter(
        int $category_id,
        array $url,
        string $configuration_name = 'trenutni'
    ): array {
        self::$url = $url;
        $configuration_name = $configuration_name === 'trenutni' ? null : $configuration_name;
        $configuration_array = ConfigurationService::getConfigurationArray($configuration_name);
        $category_name = CategoryService::getCategoryById($category_id)->name_import;
        $mapping_data = ConfiguratorMappingService::getMappingData($category_name);
        $mapping_data = $mapping_data !== null ? $mapping_data : [];
        $filters = [];
        if (!empty($configuration_array['products'])) {
            foreach ($configuration_array['products'] as $array_single) {
                foreach ($mapping_data as $single_data) {
                    if (!empty($single_data['sources']) && !empty($single_data['targets'])) {
                        $attribute = ProductService::getAttributeByName($category_id, $single_data['sources'][0]);
                        self::$attribute_value = ProductService::getAttributeValueByMachineName($array_single['product_id'], $single_data['targets'][0]);
                        self::$attribute_label = str_replace(' ', '_', $attribute->label);
                        if ($single_data['type'] === 'direct') {
                            if (self::$attribute_value !== null) {
                                $filters[self::$attribute_label] = self::$attribute_value;
                            }
                        } else if ($single_data['type'] === 'value-includes' || $single_data['type'] === 'value-included') {
                            if (self::$attribute_value !== null) {
                                self::changeUrlValueIncludes($category_id, $single_data['sources'][0], $single_data['type']);
                            }
                        } else if ($single_data['type'] === 'compare_less' || $single_data['type'] === 'sum_ram_size') {
                            if (self::$attribute_value !== null) {
                                self::changeUrlCompareLess($category_id, $single_data['sources'][0]);
                            }
                        } else if ($single_data['type'] === 'sum-<=') {
                            unset(self::$url[self::$attribute_label]);
                            if (self::$attribute_value !== null) {
                                self::changeUrlSumLess($category_id, $single_data['sources'][0], $single_data['sources'], $array_single);
                            }
                        } else if ($single_data['type'] === 'sum->=') {
                            unset(self::$url[self::$attribute_label]);
                            self::changeUrlSumMore($category_id, $single_data['sources'][0], $single_data['targets'], $array_single);
                        }
                    }
                }
            }
        }
        foreach ($filters as $key => $filter) {
            self::$url[$key] = [$filter];
        }

        return self::$url;
    }

    /**
     * Proverava da li filter sadrzi zadatu vrednost
     *
     * @param integer  $category_id    Id kategorije
     * @param string   $source         Atribut koji se proverava
     * @param string   $type           Tip poredjenja
     * @return void
     */
    private static function changeUrlValueIncludes(int $category_id, string $source, string $type): void {
        $category_attribute_values = ProductService::getAttributeValueByMachineNameCategoryId($category_id, $source);
        foreach ($category_attribute_values as $category_attribute_value) {
            if (($type === 'value-includes'
                && strpos($category_attribute_value['value'], self::$attribute_value) !== false)
                || strpos(self::$attribute_value, $category_attribute_value['value']) !== false
            ) {
                self::$url[self::$attribute_label] [] = $category_attribute_value['value'];
            }
        }
    }

    /**
     * Proverava da li je vrednost atributa kategorije manja od atributa proizvoda sa kojim se poredi
     *
     * @param integer $category_id   Id kategorije
     * @param string  $source        Atribut koji se proverava
     * @return void
     */
    private static function changeUrlCompareLess(int $category_id, string $source): void {
        $category_attribute_values = ProductService::getAttributeValueByMachineNameCategoryId($category_id, $source);
        foreach ($category_attribute_values as $category_attribute_value) {
            $clean_category_value = intval(preg_replace('~\D~', '', $category_attribute_value['value']));
            $clean_attribute_value = intval(preg_replace('~\D~', '', self::$attribute_value));
            if ($clean_category_value <= $clean_attribute_value) {
                self::$url[self::$attribute_label] [] = $category_attribute_value['value'];
            }
        }
    }

    /**
     * Proverava da li zbir atributa proizvoda manji od atributa kategorije
     *
     * @param integer $category_id   Id kategorije
     * @param string $source         Atribut kategorije koji se proverava
     * @param array $sources_array   Lista atributa proizvoda
     * @param array $product_array   Lista proizvoda
     * @return void
     */
    private static function changeUrlSumLess(int $category_id, string $source, array $sources_array, array $product_array) {
        self::$url[self::$attribute_label] [] = [];
        $category_attribute_values = ProductService::getAttributeValueByMachineNameCategoryId($category_id, $source);
        $clean_attribute_value = intval(preg_replace('~\D~', '', self::$attribute_value));
        foreach ($sources_array as $single_source) {
            $product_sum = ProductService::getAttributeValueByMachineName($product_array['product_id'], $source);
            if ($product_sum !== null) {
                $clean_product_sum = intval(preg_replace('~\D~', '', $product_sum));
                self::$sum_product_value += $clean_product_sum;
            }
        }
        if (self::$sum_product_value !== 0) {
            foreach ($category_attribute_values as $category_attribute_value) {
                $clean_category_value = intval(preg_replace('~\D~', '', $category_attribute_value['value']));
                if ($clean_category_value + self::$sum_product_value + 100 <= $clean_attribute_value * 70 / 100) {
                    self::$url[self::$attribute_label] [] = $category_attribute_value['value'];
                }
            }
        }

    }

    /**
     * Proverava da li atribut kategorije veci od zbira atributa proizvoda
     *
     * @param integer $category_id   Id kategorije
     * @param string $source         Atribut kategorije koji se proverava
     * @param array $targets_array   Lista atributa proizvoda
     * @param array $product_array   Lista proizvoda
     * @return void
     */
    private static function changeUrlSumMore(int $category_id, string $source, array $targets_array, array $product_array) {
        self::$url[self::$attribute_label] [] = [];
        $category_attribute_values = ProductService::getAttributeValueByMachineNameCategoryId($category_id, $source);
        foreach ($targets_array as $target) {
            $product_sum = ProductService::getAttributeValueByMachineName($product_array['product_id'], $target);
            if ($product_sum !== null) {
                $clean_product_sum = intval(preg_replace('~\D~', '', $product_sum));
                self::$sum_product_value += $clean_product_sum;
            }
        }
        if (self::$sum_product_value !== 0) {
            foreach ($category_attribute_values as $category_attribute_value) {
                $clean_category_value = intval(preg_replace('~\D~', '', $category_attribute_value['value']));
                if (self::$sum_product_value + 100 <= $clean_category_value * 70 / 100) {
                    self::$url[self::$attribute_label] [] = $category_attribute_value['value'];
                }
            }
        }
    }
}
