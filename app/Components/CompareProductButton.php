<?php

namespace App\Components;

use App\Components\BaseComponent;

/**
*
*/
class CompareProductButton extends BaseComponent {
    public function renderHTML($product_id = null) {
        $args = [];
        return view('CompareProductButton/templates/CompareProductButton', $args);
    }
}
