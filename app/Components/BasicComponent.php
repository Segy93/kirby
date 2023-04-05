<?php

namespace App\Components;

use App\Components\BaseComponent;

/**
*
*/
class BasicComponent extends BaseComponent {
    protected $composite = true;
    protected $css       = ['BasicComponent/css/BasicComponent.css'];
    protected $js        = ['BasicComponent/js/BasicComponent.js'];


    public function renderHTML() {
        $args = [
        ];
        return view('BasicComponent/templates/BasicComponent', $args);
    }
}
