<?php

namespace App\Components;

/**
*
*/
class FooterServices extends BaseComponent {

    protected $css = ['FooterServices/css/FooterServices.css'];

    public function renderHTML() {
        $args = [];
        return view('FooterServices/templates/FooterServices', $args);
    }
}
