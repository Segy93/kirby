<?php

namespace App\Components;

use App\Providers\ProductService;

/**
 *
 */
class OnSaleProducts extends BaseComponent {

    protected $composite = true;
    private $product_single__compact = null;

    public function __construct($product_single__compact = null) {
        if ($product_single__compact) {
            parent::__construct([$product_single__compact]);
        }

        $this->product_single__compact = $product_single__compact;
    }

    public function renderHTML() {
        $args = [
            'products'                   =>  ProductService::getProductsOnSale(),
            'product_single__compact'    =>  $this->product_single__compact
        ];
        return view('OnSaleProducts/templates/OnSaleProducts', $args);
    }
}
