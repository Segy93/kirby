<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\ProductService;

/**
*
*/
class MainMenuCompare extends BaseComponent {
    protected $js = ['MainMenuCompare/js/MainMenuCompare.js'];

    public function renderHTML() {
        $args = [
            'compare' => count($this->getComparingProducts()),
        ];

        return view('MainMenuCompare/templates/MainMenuCompare', $args);
    }



    /*CREATE*/










    /*READ*/

    private function getComparingProducts() {
        $comparing_products = array();
        $compare = ProductService::getComparingProducts();

        if (!empty($compare)) {
            foreach ($compare as $element) {
                array_push($comparing_products, $element->id);
            }
        }

        return $comparing_products;
    }




    public function fetchData() {
        return count($this->getComparingProducts());
    }
}
