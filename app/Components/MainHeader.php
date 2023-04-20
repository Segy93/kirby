<?php

namespace App\Components;

use App\Components\BaseComponent;

/**
 * h1 element
 */
class MainHeader extends BaseComponent {
    protected $css  = ['MainHeader/css/MainHeader.css'];
    protected $js   = ['MainHeader/js/MainHeader.js'];

    private $heading = 'Online prodaja Kirby kesa, delova, dodatne opreme kao i kompletnog sistema | Kese za kirby - eXelence d.o.o!';

    public function __construct($heading = null) {
        if ($heading !== null) {
            $this->heading = $heading;
        }
    }


    public function renderHTML() {
        $args = [
            'heading' => $this->heading,
        ];

        return view('MainHeader/templates/MainHeader', $args);
    }
}
