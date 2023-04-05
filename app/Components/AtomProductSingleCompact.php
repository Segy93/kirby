<?php

namespace App\Components;

use App\Components\ProductRating;

use App\Providers\ProductService;
use App\Providers\BaseService;

/**
* Koristi se za kratke prikaze produkata (sadrze sliku cenu i tekst)
*/
class AtomProductSingleCompact extends BaseComponent {
    protected $composite = true;
    protected $css = ['AtomProductSingleCompact/css/AtomProductSingleCompact.css'];

    private $product_rating = null;

    public function __construct($product_rating = null) {
        ProductService::enableImageFormatThumbnail();

        $this->product_rating = $product_rating;

        if ($product_rating === null) {
            $this->product_rating = new ProductRating();
        }

        if ($this->product_rating) {
            parent::__construct([$this->product_rating]);
        }
    }

    public function renderHTML($product = null) {
        $tommorow = new \DateTime('tomorrow');
        $args = [
            'product'           => $product,
            'product_rating'    => $this->product_rating,
            'js_template'       => $product === null,
            'tommorow'          => $tommorow->format('Y-m-d H:i:s'),
            'protocol'          => BaseService::getProtocol(),
        ];
        return view('AtomProductSingleCompact/templates/AtomProductSingleCompact', $args);
    }
}
