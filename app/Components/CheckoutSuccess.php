<?php

namespace App\Components;

use App\Components\BaseComponent;

/**
*
*/
class CheckoutSuccess extends BaseComponent {
    public function renderHTML() {
        $args = [];

        return view('CheckoutSuccess/templates/CheckoutSuccess', $args);
    }
}
