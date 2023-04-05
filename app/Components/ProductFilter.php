<?php

namespace App\Components;

use App\Providers\CategoryService;
use App\Providers\ConfiguratorService;

/**
 * Filteri za proizvode
 */
class ProductFilter extends BaseComponent {
    protected $js   = ['ProductFilter/js/ProductFilter.js'];
    protected $css  = ['ProductFilter/css/ProductFilter.css'];
    private $url = [];
    private $category_id;
    private $on_sale = false;
    private $is_configurator = false;
    private $category = null;

    /**
     * Konstruktor
     *
     * @param array   $url                 Niz sa filterima iz url-a
     * @param integer $category_id         Id kategorije
     * @param boolean $on_sale             Da li je na rasprodaji
     * @param boolean $is_configurator     Da li je konfigurator
     * @param string  $configuration_name  Naziv konfiguratora
     */
    public function __construct(
        array $url = [],
        $category_id = 0,
        $on_sale = false,
        bool $is_configurator = false,
        string $configuration_name = 'trenutni'
    ) {
        $this->category = CategoryService::getCategoryById($category_id);
        if (array_key_exists('price_retail', $url)) {
            $url['price_retail'][0] =  preg_replace('/Max\:/', '', $url['price_retail'][0]);
            $url['price_retail'][0] =  preg_replace('/Min\:/', '', $url['price_retail'][0]);
            $url['price_retail'][0] =  explode('-', $url['price_retail'][0]);
        }
        if ($is_configurator) {
            $url = ConfiguratorService::changeUrlFilter($category_id, $url, $configuration_name);
            $url['stock'][] = 'Raspoloživo';
            $url['Proizvođač'][] = 'Asus';
            if ($this->category->name === 'Kućišta') {
                $url['Napajanje'][] = 'Bez napajanja';
            }
        }
        $this->url             = $url;
        $this->category_id     = $category_id;
        $this->on_sale         = $on_sale;
        $this->is_configurator = $is_configurator;
    }


    public function renderHTML() {
        $filters    =   CategoryService::getCategoryFilters($this->category_id);

        $args = [
            'on_sale'         => $this->on_sale,
            'filters'         => $filters,
            'url'             => $this->url,
            'category'        => $this->category,
            'is_configurator' => $this->is_configurator,
        ];

        return view('ProductFilter/templates/ProductFilter', $args);
    }
}
