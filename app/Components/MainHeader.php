<?php

namespace App\Components;

use App\Components\BaseComponent;

/**
 * h1 element
 */
class MainHeader extends BaseComponent {
    protected $css  = ['MainHeader/css/MainHeader.css'];
    protected $js   = ['MainHeader/js/MainHeader.js'];

    private $heading = 'Kirby servis, kese, rezervni delovi, dodatna oprema kao i kompletni sistem <br/> Kese za Kirby - eXelence d.o.o.';

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
