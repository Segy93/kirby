<?php

namespace App\Components;

use App\Providers\UserService;
use App\Providers\ShopService;

use Illuminate\Support\Facades\URL;

/**
 *
 */
class OrderList extends BaseComponent {
    protected $js       = ['OrderList/js/OrderList.js'];
    protected $css      = ['OrderList/css/OrderList.css'];

    public function renderHTML() {
        $args = [
            'orders'    => ShopService::getOrdersByUserId(UserService::getCurrentUserId()),
            'path'      => URL::current(),
        ];

        return view('OrderList/templates/OrderList', $args);
    }









    /*Create*/









    /*Read*/









    /*Update*/


    public function confirmOrder($params) {
        $order_id = intval($params['order_id']);
        $confirm = ShopService::createOrderUpdate(
            $order_id,
            null,
            'potvrđeno',
            null,
            'Potvrđena narudžbina',
            false
        );
        return $confirm;
    }

    public function returnToCart($params) {
        $order_id = $params['order_id'];
        return ShopService::cancelUnconfirmedOrder(null, $order_id);
    }
}
