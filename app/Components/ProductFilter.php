<?php

namespace App\Components;

use App\Providers\CategoryService;

/**
 * Filteri za proizvode
 */
class ProductFilter extends BaseComponent {
    protected $js   = ['ProductFilter/js/ProductFilter.js'];
    protected $css  = ['ProductFilter/css/ProductFilter.css'];
    private $url = [];
    private $category_id;
    private $on_sale = false;
    private $category = null;

    /**
     * Konstruktor
     *
     * @param array   $url                 Niz sa filterima iz url-a
     * @param integer $category_id         Id kategorije
     * @param boolean $on_sale             Da li je na rasprodaji
     */
    public function __construct(
        array $url = [],
        $category_id = 0,
        $on_sale = false
    ) {
        $this->category = CategoryService::getCategoryById($category_id);
        if (array_key_exists('price_retail', $url)) {
            $url['price_retail'][0] =  preg_replace('/Max\:/', '', $url['price_retail'][0]);
            $url['price_retail'][0] =  preg_replace('/Min\:/', '', $url['price_retail'][0]);
            $url['price_retail'][0] =  explode('-', $url['price_retail'][0]);
        }
        $this->url             = $url;
        $this->category_id     = $category_id;
        $this->on_sale         = $on_sale;
    }


    public function renderHTML() {
        $filters    =   CategoryService::getCategoryFilters($this->category_id);

        $args = [
            'on_sale'         => $this->on_sale,
            'filters'         => $filters,
            'url'             => $this->url,
            'category'        => $this->category,
        ];

        return view('ProductFilter/templates/ProductFilter', $args);
    }
}
