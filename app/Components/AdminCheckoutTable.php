<?php

namespace App\Components;

use App\Components\BaseComponent;

use App\Providers\ShopService;
use App\Providers\UserService;

/**
*
*/
class AdminCheckoutTable extends BaseComponent {
    protected $composite = true;
    protected $css       = ['AdminCheckoutTable/css/AdminCheckoutTable.css'];
    protected $js        = ['AdminCheckoutTable/js/AdminCheckoutTable.js'];

    private $order_id = null;

    public function __construct($order_id = null) {
        $this->order_id = $order_id;
    }

    public function renderHTML() {
        $order      = ShopService::getOrderById($this->order_id);
        $args = [
            'order'         => $order,
            'products'      => ShopService::getOrderProductsByOrderId($order->id),
            'user_info'     => UserService::getUserById($order->user->id),
        ];
        return view('AdminCheckoutTable/templates/AdminCheckoutTable', $args);
    }
}
