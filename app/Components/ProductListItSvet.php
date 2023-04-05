<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\ProductService;
use Illuminate\View\View;

/**
 * Spisak proizvoda, koji onda IT svet koristi
 */
class ProductListItSvet extends BaseComponent {
    public function renderHTML(): View {
        /** @var \App\Models\Product[] */
        $products = ProductService::getAllProductsPublished();
        $args = [
            'products' => $products,
        ];

        return view('ProductListItSvet/templates/ProductListItSvet', $args);
    }
}
