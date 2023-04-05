<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\SessionService;
use App\Providers\ShopService;
use App\Providers\UserService;
use App\Providers\WishListService;

/**
*
*/
class AtomWishListToggle extends BaseComponent {
    protected $css      = ['AtomWishListToggle/css/AtomWishListToggle.css'];
    protected $js       = ['AtomWishListToggle/js/AtomWishListToggle.js'];
    protected $icons    = ['AtomWishListToggle/templates/AddTo'];

    private $button_mode = '';

    public function __construct(string $button_mode = '') {
        $this->button_mode = $button_mode;
    }

    public function renderHTML($product_id = null) {
        $args = [
            'product_id'   => $product_id,
            'in_wishlist'  => $product_id === null ? null : $this->isInWishList($product_id),
            'js_template'  => $product_id === null,
            'csrf_field'   => SessionService::getCsrfField(),
            'button_mode'  => $this->button_mode,
        ];

        return view('AtomWishListToggle/templates/AtomWishListToggle', $args);
    }










    /*CREATE*/










    /*READ*/










    private function isInWishList($product_id) {

        $wishlist = WishListService::getWishListByUserId();
        $in_wishlist = false;

        foreach ($wishlist as $element) {
            if ($element->product->id === $product_id) {
                $in_wishlist = true;
            }
        }
        return $in_wishlist;
    }

    public function getUsersWishlist() {
        $wish = [];


        if (is_numeric(UserService::getCurrentUserId())) {
            $wishList = WishListService::getWishListByUserId(UserService::getCurrentUserId());

            foreach ($wishList as $element) {
                $wish[] = $element->id;
            }
        }
        return $wish;
    }

    private function getUsersCart() {
        $cart = [];


        if (is_numeric(UserService::getCurrentUserId())) {
            $Cart = ShopService::getUserCartByUserId(UserService::getCurrentUserId());

            foreach ($Cart as $element) {
                $cart[] = $element->id;
            }
        }

        return $cart;
    }










    /*UPDATE*/










    public function changeWish($params) {
        $product_id     = intval($params['product_id']);
        $in_wishlist    = boolval($params['in_wishlist']);
        $user_id        = UserService::getCurrentUserId();

        if ($in_wishlist) {
            return WishListService::addToList($product_id, $user_id);
        } else {
            return WishListService::deleteFromListByProductIdUserId($product_id, $user_id);
        }
    }










    /*DELETE*/
}
