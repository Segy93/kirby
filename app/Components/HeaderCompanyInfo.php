<?php

namespace App\Components;

use App\Components\BaseComponent;

/**
*
*/
class HeaderCompanyInfo extends BaseComponent {
    protected $css       = ['HeaderCompanyInfo/css/HeaderCompanyInfo.css'];

    public function renderHTML() {
        $args = [
        ];
        return view('HeaderCompanyInfo/templates/HeaderCompanyInfo', $args);
    }
}
