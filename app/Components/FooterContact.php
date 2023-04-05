<?php

namespace App\Components;

use App\Providers\ShopService;

/**
*
*/
class FooterContact extends BaseComponent {

    protected $css = ['FooterContact/css/FooterContact.css'];
    public function renderHTML() {
        $args = [
            'shops'  =>  ShopService::getShops()
        ];
        return view('FooterContact/templates/FooterContact', $args);
    }
}
