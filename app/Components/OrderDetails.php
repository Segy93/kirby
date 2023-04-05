<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\ShopService;

/**
 *
 */
class OrderDetails extends BaseComponent {
    // protected $composite = true;
    protected $css       = ['OrderDetails/css/OrderDetails.css'];
    protected $js        = ['OrderDetails/js/OrderDetails.js'];

    private $order_id = null;

    public function __construct($order_id = null) {
        $this->order_id = $order_id;
    }

    public function renderHTML() {
        $args = [
            'order' => ShopService::getOrderById($this->order_id),
        ];
        return view('OrderDetails/templates/OrderDetails', $args);
    }
}
