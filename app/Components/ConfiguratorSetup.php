<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\CategoryService;
use App\Providers\ConfigurationService;
use App\Providers\ProductService;
use App\Providers\SessionService;

/**
* Komponenta za prikaz strane konfiguratora
*/
class ConfiguratorSetup extends BaseComponent {
    protected $composite = true;
    protected $css       = [
        'ConfiguratorSetup/css/ConfiguratorSetup.css',
        'libs/plugins/sweetalert/sweetalert2.min.css',
    ];
    protected $js        = [
        'ConfiguratorSetup/js/ConfiguratorSetup.js',
        'libs/plugins/sweetalert/sweetalert2.min.js',
    ];

    private static $component_name = 'ConfiguratorSetup';

    /**
     * Sve kategorije koje se mogu dodavati u konfiguratoru
     * sa njihovim opcijama
     *
     * @var array
     */
    private static $configurator_categories = [
        'procesori' => [
            'message'        => 'Procesor',
            'img'            => 'procesor.svg',
            'allow_multiple' => false,
        ],
        'COOL' => [
            'message'        => 'Procesorski kuler',
            'img'            => 'procesorski_kuler.svg',
            'allow_multiple' => false,
        ],
        'Matične ploče' => [
            'message'        => 'Matična ploča',
            'img'            => 'maticna_ploca.svg',
            'allow_multiple' => false,
        ],
        'graficke karte' => [
            'message'        => 'Grafička karta',
            'img'            => 'graphic_card.svg',
            'allow_multiple' => false
        ],
        'ram' => [
            'message'        => 'RAM memorija',
            'img'            => 'RAM_memorija.svg',
            'allow_multiple' => true
        ],
        'HD' => [
            'message'        => 'Hard disk',
            'img'            => 'hard_disk.svg',
            'allow_multiple' => true
        ],
        'HD SSD' => [
            'message'        => 'SSD',
            'img'            => 'ssd.svg',
            'allow_multiple' => true
        ],
        'OPTIKA' => [
            'message'        => 'Optički uređaj',
            'img'            => 'opticki_uredjaj.svg',
            'allow_multiple' => true
        ],
        'CASE' => [
            'message'        => 'Kućište',
            'img'            => 'kuciste.svg',
            'allow_multiple' => false
        ],
        'PSU' => [
            'message'        => 'Napajanje',
            'img'            => 'napajanje.svg',
            'allow_multiple' => false
        ],
        'WiFi' => [
            'message'        => 'Wifi kartica/adapter',
            'img'            => 'WiFi.svg',
            'allow_multiple' => false
        ],
        'MON' => [
            'message'        => 'Monitor',
            'img'            => 'monitor.svg',
            'allow_multiple' => true,
        ],
        'KBRD' => [
            'message'        => 'Tastatura',
            'img'            => 'tastatura.svg',
            'allow_multiple' => false,
        ],
        'MOUSE' => [
            'message'        => 'Miš',
            'img'            => 'mis.svg',
            'allow_multiple' => false,
        ],
        'PODLOGE' => [
            'message'        => 'Podloga za miš',
            'img'            => 'podloga_mis.svg',
            'allow_multiple' => false,
        ],
        'HS' => [
            'message'        => 'Slušalice',
            'img'            => 'slusalice.svg',
            'allow_multiple' => false,
        ],
    ];

    private $products_list = null;
    private $configuration_name = null;

    public function __construct(?string $configuration_name = null) {
        $components = [];

        $products_list = new ConfiguratorProductList($configuration_name);
        $components[] = $products_list;
        parent::__construct($components);

        $this->products_list = $products_list;
        $this->configuration_name = $configuration_name;
    }

    public function renderHTML() {
         // ovde treba da se dohvati korisnikova konfiguracija iz sesije ili baze
        $configuration_array = ConfigurationService::getConfigurationArray($this->configuration_name);
        $categories = self::getConfiguratorCategories();
        $selected_products = ConfigurationService::getUsersSelectedProducts($configuration_array);
        $configuration = null;
        if ($this->configuration_name !== null) {
            $configuration = ConfigurationService::getConfigurationByName($this->configuration_name);
        }
        $configuration_id = $configuration !== null
            ? $configuration->id
            : null
        ;
        $configuration_key = $configuration_id === null ? 0 : $configuration_id;
        $session_key = 'configuration_error_' . $configuration_key;
        $session_key_configuration = 'configuration_name_' . $configuration_key;
        $configuration_name_session = SessionService::getSessionValueForService($session_key_configuration, self::$component_name);
        $errors = SessionService::getSessionValueForService($session_key, 'ConfigurationService');
        $args = [
            'categories_config'          => self::$configurator_categories,
            'categories'                 => $categories,
            'selected_products'          => $selected_products,
            'products_list'              => $this->products_list,
            'configuration_name_url'     => $this->configuration_name === null
                ? ConfigurationService::$reserved_name
                : urlencode($this->configuration_name)
            ,
            'configuration_name_session' => $configuration_name_session !== null
                ? $configuration_name_session
                : $this->configuration_name
            ,
            'configuration_name'         => $this->configuration_name === null
                ? null
                : $this->configuration_name
            ,
            'configuration_id'           => $configuration_id,
            'total_price'                => $selected_products['total_price'],
            'errors'                     => !empty($errors) ? $errors : [],
            'csrf_field'                 => SessionService::getCsrfField(),
            'name_taken'                 => SessionService::getSessionValueForService('configuration_name_taken', 'ConfigurationService'),
        ];
        return view('ConfiguratorSetup/templates/ConfiguratorSetup', $args);
    }

    /**
     * Doihvata objekte kategorija koje se koriste u konfiguratoru
     *
     * @return array
     */
    public static function getConfiguratorCategories(): array {
        $categories = [];
        foreach (self::$configurator_categories as $category => $values) {
            array_push($categories, CategoryService::getCategoryByNameImport($category));
        }

        return $categories;
    }

    /**
     * Cuva ime konfiguracije u sesiji
     *
     * @param array  $params           Niz parametara
     *  param string $params['name']   Ime konfiguracije koje se cuva
     * @return void
     */
    public static function updateName(array $params): void {
        $name = $params['name'];
        $id = $params['id'] !== null ? (int)$params['id'] : 0;
        ConfigurationService::updateConfigurationNameSession($name, $id, self::$component_name);
    }
}
