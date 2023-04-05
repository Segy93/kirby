<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Components\MainMenuCart;
use App\Components\MainMenuCompare;
use App\Components\MainMenuWishList;
use App\Providers\ShopService;
use App\Providers\UserService;

/**
 *
 */
class UserMenuWidget extends BaseComponent {
    private $cart = null;
    private $wishList = null;
    protected $composite  = true;

    protected $css  = ['UserMenuWidget/css/UserMenuWidget.css'];
    protected $js   = ['UserMenuWidget/js/UserMenuWidget.js'];

    public function __construct() {
        $this->wishList   = new MainMenuWishList();
        $this->cart       = new MainMenuCart();
        $this->compare    = new MainMenuCompare();

        parent::__construct([
            $this->cart,
            $this->wishList,
            $this->compare,
        ]);
    }

    public function renderHTML() {
        $logged_in = UserService::isUserLoggedIn();
        $notify    = false;

        if ($logged_in) {
            $last_order = ShopService::hasUnconfirmedOrders(UserService::getCurrentUserId());

            $notify = $last_order;
        }

        $args = [
            'isLoggedIn'    =>  $logged_in,
            'userId'        =>  $logged_in ? UserService::getCurrentUserId() : 0,
            'wishList'      =>  $this->wishList,
            'cart'          =>  $this->cart,
            'compare'       =>  $this->compare,
            'user'          =>  $logged_in ? UserService::getCurrentUser() : null,
            'notify'        =>  $notify,
        ];

        return view('UserMenuWidget/templates/UserMenuWidget', $args);
    }



    public function checkNotification() {
        $logged_in = UserService::isUserLoggedIn();
        $notify    = false;
        if ($logged_in) {
            $last_order = ShopService::getOrderByUserIdStatus(UserService::getCurrentUserId());

            $notify = $last_order === null ? false : true;
        }

        return $notify;
    }
}
