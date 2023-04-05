<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\SessionService;
use App\Providers\ShopService;
use App\Providers\UserService;

/**
 * Taster za dodavanje stvari u korpu
 */
class AtomCartToggle extends BaseComponent {
    protected $css = [
        'AtomCartToggle/css/AtomCartToggle.css',
        'libs/plugins/sweetalert/sweetalert2.min.css',
    ];

    protected $js = [
        'AtomCartToggle/js/AtomCartToggle.js',
        'libs/plugins/sweetalert/sweetalert2.min.js'
    ];
    protected $icons = ['AtomCartToggle/templates/icons'];

    private $button_mode = '';

    public function __construct(string $button_mode = '') {
        $this->button_mode = $button_mode;
    }

    public function renderHTML($product_id = null) {
        $args = [
            'product_id'   =>  $product_id,
            'in_cart'      =>  $product_id === null ? null : $this->isInCart($product_id),
            'quantity'     =>  $product_id === null ? null : $this->getQuantity($product_id),
            'js_template'  =>  $product_id === null,
            'csrf_field'   =>  SessionService::getCsrfField(),
            'button_mode'  =>  $this->button_mode,
        ];
        return view('AtomCartToggle/templates/AtomCartToggle', $args);
    }










    /**
     *
     * CREATE
     *
     */










    /**
     *
     * READ
     *
     */





    private function isInCart($product_id) {

        $cart = ShopService::getUserCartByUserId();
        $in_cart = false;

        foreach ($cart as $element) {
            if ($element->product->id === $product_id) {
                $in_cart = true;
            }
        }
        return $in_cart;
    }





    private function getQuantity($product_id) {
        $cart = ShopService::getUserCartByUserId();
        foreach ($cart as $element) {
            if ($element->product->id === $product_id) {
                return $element->quantity;
            }
        }
        return 0;
    }

    public function getUserCart() {
        $cart = [];

        $user_cart = ShopService::getUserCartByUserId(UserService::getCurrentUserId());
        foreach ($user_cart as $row) {
            $cart[] = $row->id;
        }

        return $cart;
    }










    /**
     *
     * UPDATE
     *
     */










    public function changeCart($params) {
        $product_id     = intval($params['product_id']);
        $quantity       = $params['quantity'] === 0 ? 1 : intval($params['quantity']);
        $in_cart        = boolval($params['in_cart']);
        $user_id        = UserService::getCurrentUserId();
        if ($in_cart) {
            return ShopService::changeCart($product_id, $quantity, $user_id);
        } else {
            return ShopService::deleteCartItem($user_id, $product_id);
        }
    }
}
