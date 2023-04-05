<?php

namespace App\Components;

use App\Providers\ShopService;
use App\Providers\UserService;
use App\Providers\WishListService;

/**
 *
 */
class WishList extends BaseComponent {
    private $user_id    = null;
    protected $js       = ['WishList/js/WishList.js'];
    protected $css      = ['WishList/css/WishList.css'];
    protected $icons    = ['WishList/templates/icons'];

    public function renderHTML() {
        $args = [
            'wishlist' => WishListService::getWishListByUserId(UserService::getCurrentUserId()),
        ];

        return view('WishList/templates/WishList', $args);
    }









    /*Create*/



    public function addToCart($params) {
        $product_id = $params['id'];
        $user_id    = UserService::getCurrentUserId();
        return ShopService::changeCart($product_id, 1, $user_id);
    }






    /*Read*/









    /*Update*/









    /*Delete*/

    public function removeWish($params) {
        $wishlist_id = $params['id'];
        return WishListService::deleteFromList($wishlist_id);
    }
}
