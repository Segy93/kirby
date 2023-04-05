<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\ConfigurationService;
use App\Providers\SessionService;

/**
* Prikaz liste odabranih proizvoda na konfiguratoru
*/
class ConfiguratorProductList extends BaseComponent {
    protected $css       = ['ConfiguratorProductList/css/ConfiguratorProductList.css'];
    protected $js        = ['ConfiguratorProductList/js/ConfiguratorProductList.js'];

    private $configuration_name = null;

    /**
     * Konstruktor
     *
     * @param   string  $configuration_name  Naziv konfiguracije
     */
    public function __construct(?string $configuration_name = null) {
        $this->configuration_name = $configuration_name;
    }

    public function renderHTML(?array $products = null) {
        $args = [
            'products'              => $products,
            'configuration_name'    => $this->configuration_name,
            'csrf_field'            => SessionService::getCsrfField(),
        ];
        return view('ConfiguratorProductList/templates/ConfiguratorProductList', $args);
    }

    /**
     * Brise proizvod iz zadate konfiguracije
     *
     * @param array $params
     * @return string
     */
    public function removeProductFromConfiguratorList(array $params): string {
        $product_id = intval($params['product_id']);
        $name = $params['configuration_name'];
        $configuration_id = null;
        $configuration_name = null;
        if ($name !== '') {
            $configuration_id = ConfigurationService::getConfigurationIdByName($name);
            $configuration_name = $name;
        }
        ConfigurationService::deleteConfigurationProduct($product_id, $configuration_id);
        $configuration_array = ConfigurationService::getConfigurationArray($configuration_name);
        return ConfigurationService::getUsersSelectedProducts($configuration_array)['total_price'];
    }
}
