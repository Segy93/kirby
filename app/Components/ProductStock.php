<?php

namespace App\Components;

use App\Components\BaseComponent;

use App\Models\Product;

/**
 *
 */
class ProductStock extends BaseComponent {
    protected $css = ['ProductStock/css/ProductStock.css'];
    public function renderHTML(Product $product = null) {
        $js_template = $product === null;

        $args = [
            'js_template' => $js_template,

            'status' => [
                'Magacin' => $js_template ? 'in_warehouse' : $product->inWarehouse(),
                'Radnja' => $js_template ? 'in_shop' : $product->inShop(),
            ],
        ];
        return view('ProductStock/templates/ProductStock', $args);
    }
}
