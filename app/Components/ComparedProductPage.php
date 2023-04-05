<?php

namespace App\Components;

use App\Components\AtomCartToggle;
use App\Components\AtomWishListToggle;
use App\Components\BaseComponent;
use App\Providers\CategoryService;
use App\Providers\ProductService;

/**
*
*/
class ComparedProductPage extends BaseComponent {

    protected $composite        = true;

    protected $wishListToggle   = null;
    protected $cartToggle       = null;

    protected $css  = ['ComparedProductPage/css/ComparedProductPage.css'];
    protected $js   = ['ComparedProductPage/js/ComparedProductPage.js'];










    public function __construct() {
        $button_mode = 'light';
        $this->cartToggle       = new AtomCartToggle($button_mode);
        $this->wishListToggle   = new AtomWishListToggle($button_mode);
        parent::__construct([
            $this->wishListToggle,
            $this->cartToggle,
        ]);
    }






    private function getCategoryIDs($products) {
        $category_ids = [];
        foreach ($products as $product) {
            $category_ids[] = $product->category_id;
        }
        return $category_ids;
    }

    private function formatData($products, $category_attributes) {
        $data_products_raw = [];
        foreach ($products as $product) {
            $data_product = [];

            $attributes = $product->attribute_values('label');
            foreach ($attributes as $attribute_value) {
                $label = $attribute_value->attribute->label;
                $data_product[$label] = $attribute_value->value;
            }

            foreach ($category_attributes as $label) {
                if (array_key_exists($label, $data_product) === false) {
                    $data_product[$label] = '';
                }
            }

            $data_product['product_id'] = $product->id;

            $data_products_raw[] = $data_product;
        }

        $attributes_used = [];
        foreach ($category_attributes as $label) {
            $has_content = false;
            foreach ($data_products_raw as $data_product) {
                if (!empty($data_product[$label])) {
                    $has_content = true;
                    break;
                }
            }

            if ($has_content) {
                array_push($attributes_used, $label);
            }
        }

        return [$attributes_used, $data_products_raw];
    }

    public function renderHTML() {
        $products = ProductService::getComparingProducts();
        $category_ids = $this->getCategoryIDs($products);
        $attributes_all = CategoryService::getCategoryAttributes($category_ids);

        list($attributes_used, $data) = $this->formatData($products, $attributes_all);
        $args = [
            'attributes'        =>  $attributes_used,
            'data'              =>  $data,
            'products'          =>  $products,
            'wishListToggle'    =>  $this->wishListToggle,
            'cartToggle'        =>  $this->cartToggle,
        ];

        return view('ComparedProductPage/templates/ComparedProductPage', $args);
    }



    /*CREATE*/










    /*READ*/












    /*UPDATE*/










    public function changeCompare($params) {
        $product_id = $params['product_id'];
        $in_compare = $params['in_compare'];
        //$status?
        ProductService::addProductIdForComparison($product_id);//:
        //ProductService::deleteProductIdForComparison($product_id);
    }



    /*DELETE*/

    public function removeCompare($params) {
        $product_id = $params['id'];
        return ProductService::removeProductIdFromComparison($product_id);
    }
}
