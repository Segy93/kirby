<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\SessionService;
use App\Providers\ShopService;
use App\Providers\UserService;


/**
 * Korpa, tabela sa listom proizvoda koji su u njoj
 */
class Cart extends BaseComponent {

    protected $css        = [
        'Cart/css/Cart.css',
        'libs/plugins/sweetalert/sweetalert2.min.css',
    ];
    protected $js         = [
        'Cart/js/Cart.js',
        'libs/plugins/sweetalert/sweetalert2.min.js',
    ];

    public function renderHTML() {
        $args = [
            'cart'          => ShopService::getUserCartByUserId(UserService::getCurrentUserId()),
            'csrf_field'    => SessionService::getCsrfField(),
            'errors'        => SessionService::getSessionValueForService('changeCartErrors', 'HomeController'),
        ];

        return view('Cart/templates/Cart', $args);
    }




    public function getUserCart() {
        return ShopService::getUserCartCurrent();
    }


    /*Update*/






    public function changeCart($params) {
        $product_id = $params['product_id'];
        $quantity   = $params['quantity'] === 0 ? 1 : intval($params['quantity']);
        $user_id    = UserService::getCurrentUserId();
        $user_cart  = ShopService::getUserCartByUserId($user_id);

        return ShopService::changeCart($product_id, $quantity, $user_id);
    }



    /*Delete*/






    public function deleteCartItem($params) {
        $id = intval($params['id']);
        return ShopService::deleteCart($id);
    }
}
