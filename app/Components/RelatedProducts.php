<?php

namespace App\Components;

use App\Components\AtomProductSingleCompact;
use App\Providers\ProductService;

/**
 *
 */
class RelatedProducts extends BaseComponent {

    protected $composite = true;
    protected $css = ['RelatedProducts/css/RelatedProducts.css'];

    private $product_single__compact    = null;
    private $category_id = 0;
    private $product_id = 0;
    private $price = 0;

    public function __construct($category_id, $product_id, $price) {
        $this->product_single__compact = new AtomProductSingleCompact();
        $this->category_id = $category_id;
        $this->product_id = $product_id;
        $this->price = $price;

        parent::__construct([$this->product_single__compact]);
    }

    public function renderHTML() {
        $similar = ProductService::getSimilarProducts(
            $this->category_id,
            $this->product_id,
            $this->price
        );

        $args = [
            'products'                   => $similar,
            'product_single__compact'    => $this->product_single__compact,
        ];
        return view('RelatedProducts/templates/RelatedProducts', $args);
    }
}
