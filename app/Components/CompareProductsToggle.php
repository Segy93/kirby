<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\ProductService;
use App\Providers\SessionService;

/**
*
*/
class CompareProductsToggle extends BaseComponent {
    protected $css   = ['CompareProductsToggle/css/CompareProductsToggle.css'];
    protected $js    = ['CompareProductsToggle/js/CompareProductsToggle.js'];
    protected $icons = ['CompareProductsToggle/templates/icons'];

    public function renderHTML($product_id = null) {
        if (gettype(ProductService::getComparingProducts()) === "array") {
            $comparing = $this->getComparingProductsIds();
        }

        $args = [
            'product_id'   =>  $product_id,
            'in_compare'   =>  $product_id === null ? null : $this->isInCompare($product_id),
            'js_template'  =>  $product_id === null,
            'csrf_field'   =>  SessionService::getCsrfField(),
        ];
        return view('CompareProductsToggle/templates/CompareProductsToggle', $args);
    }



    /*CREATE*/










    /*READ*/

    public function getComparingProductsIds() {
        $compared = ProductService::getComparingProducts();

        $ids = [];
        foreach ($compared as $product) {
            array_push($ids, $product->id);
        }
        return $ids;
    }

    private function isInCompare($product_id) {

        $compare = ProductService::getComparingProducts();
        $in_compare = false;

        foreach ($compare as $element) {
            if ($element->id === $product_id) {
                $in_compare = true;
                return $in_compare;
            }
        }
    }









    /*UPDATE*/










    public function changeCompare($params) {
        $product_id = intval($params['product_id']);
        $in_compare = boolval($params['in_compare']);

        if ($in_compare) {
            return ProductService::addProductIdForComparison($product_id);
        } else {
            return ProductService::removeProductIdFromComparison($product_id);
        }
    }
}
