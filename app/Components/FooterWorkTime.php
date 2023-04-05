<?php

namespace App\Components;

use App\Providers\ShopService;

/**
*
*/
class FooterWorkTime extends BaseComponent {

    protected $css = ['FooterWorkTime/css/FooterWorkTime.css'];
    public function renderHTML() {
        $args = [
            'shops'  =>  ShopService::getShops()
        ];

        return view('FooterWorkTime/templates/FooterWorkTime', $args);
    }
}
