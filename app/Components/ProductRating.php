<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\ProductService;
use App\Providers\SessionService;

/**
 *
 */
class ProductRating extends BaseComponent {
    protected $css = [
        'ProductRating/css/ProductRating.css',
    ];

    protected $js = [
        'ProductRating/js/ProductRating.js',
    ];

    protected $rating_count = 0;

    public function renderHTML($product_id = null) {
        $args = [
            'rating'        => $product_id === null ? 0 : $this->getProductRating($product_id),
            'product_id'    => $product_id,
            'js_template'   => $product_id === null,
            'rating_count'  => $this->rating_count,
            'csrf_field'    => SessionService::getCsrfField(),
        ];

        return view('ProductRating/templates/ProductRating', $args);
    }



    /*CREATE*/










    /*READ*/

    public function refreshProductRating($params) {
        $product_id  = $params['product_id'];
        return $this->getProductRating($product_id);
    }

    public function getProductRating($product_id) {
        if ($product_id != null) {
            $product        = ProductService::getProductById($product_id);
            $rating_sum     = $product->rating_sum;
            $rating_count   = $product->rating_count;
            $this->rating_count = $rating_count;
            if ($rating_count === 0 || $rating_sum === 0) {
                return number_format(5, 1);
            } else {
                $rating_final   = number_format(($rating_sum / $rating_count), 1);
                return $rating_final;
            }
        }
    }












    /*UPDATE*/

    public function ratingAdd($params) {
        $score      = intval($params['rating']);
        $product_id = intval($params['product_id']);

        return ProductService::vote($product_id, $score);
    }
}
