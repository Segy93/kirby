<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\ShopService;

/**
 *
 */
class MainMenuCart extends BaseComponent {
    protected $js = ['MainMenuCart/js/MainMenuCart.js'];
    public function renderHTML() {
        $args = [
            'cart' => count($this->getUsersCart()),
        ];

        return view('MainMenuCart/templates/MainMenuCart', $args);
    }



    /*CREATE*/










    /*READ*/


    private function getUsersCart() {
        $cart =  array();
        // Log:info(gettype($cart));
        $user_cart = ShopService::getUserCartByUserId();
        if (!empty($user_cart)) {
            // Log:info(gettype($user_cart));
            foreach ($user_cart as $element) {
                // Log:info(gettype($element));
                array_push($cart, $element->id);
                //$cart[] = $element->id;
            }
        }

        return $cart;
    }


    public function fetchData() {
        return count($this->getUsersCart());
    }
}
