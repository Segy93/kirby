<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\SessionService;
use App\Providers\ShopService;
use App\Providers\UserService;


/**
*
*/
class CheckoutTable extends BaseComponent {
    protected $css = ['CheckoutTable/css/CheckoutTable.css'];

    public function renderHTML() {
        $silent = true;
        $user_id    = UserService::getCurrentUserId();
        $order      = ShopService::getOrderByUserIdStatus($user_id);

        $args = [
            'csrf_field'    => SessionService::getCsrfField(),
            'order'         => $order,
            'products'      => ShopService::getOrderProductsByOrderId($order->id),
            'user_info'     => UserService::getUserById($user_id),
        ];
        if ($order->delivery_address->address_type !== 'shop') {
            ShopService::calculateOrderShippingFee($order->id, $order->total_price, $silent);
        }
        return view('CheckoutTable/templates/CheckoutTable', $args);
    }
}
