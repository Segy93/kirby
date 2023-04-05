<?php

namespace App\Components;

use App\Providers\ProductService;

/**
*
*/
class FeaturedProducts extends BaseComponent {

    protected $composite                  = true;
    protected $css                        = ['FeaturedProducts/css/FeaturedProducts.css'];
    protected $js                         = ['FeaturedProducts/js/FeaturedProducts.js'];
    private $product_single__compact    = null;
    private $categories                 = [];
    private $name                       = '';
    private $limit                      = 6;

    public function __construct($product_single__compact = null, $categories = [], $name = '') {
        if ($product_single__compact) {
            parent::__construct([$product_single__compact]);
        }

        $this->product_single__compact  = $product_single__compact;
        $this->name                     = $name;
        $this->categories               = $categories;
    }

    public function renderHTML() {
        $products = ProductService::getFeaturedProducts($this->categories, $this->limit);
        $show_category = false;
        foreach ($this->categories as $category) {
            if (!empty($products[$category])) {
                $show_category = true;
            }
        }
        $args = [
            'categories'                 =>  $this->categories,
            'categories_products'        =>  $products,
            'product_single__compact'    =>  $this->product_single__compact,
            'name'                       =>  $this->name,
            'show_category'              =>  $show_category,
        ];

        return view('FeaturedProducts/templates/FeaturedProducts', $args);
    }
}
