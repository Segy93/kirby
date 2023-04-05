<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\ShopService;

/**
*
*/
class InfoShippingCost extends BaseComponent {
    protected $css       = ['InfoShippingCost/css/InfoShippingCost.css'];

    public function renderHTML($price_discount = null) {
        $args = [
            'price_discount' =>  ShopService::isShippingFree($price_discount),
        ];
        return view('InfoShippingCost/templates/InfoShippingCost', $args);
    }
}
