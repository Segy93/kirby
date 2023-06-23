<?php

namespace App\Components;

use App\Components\BaseComponent;

/**
 * h1 element
 */
class MainHeader extends BaseComponent {
    protected $css  = ['MainHeader/css/MainHeader.css'];
    protected $js   = ['MainHeader/js/MainHeader.js'];

    private $heading = 'Kirby servis <br/> kese, rezervni delovi, dodatna oprema i kompletni sistem <br/> Banovo Brdo, Čukarica, <a href = "tel:+38163223242">063/22-32-42</a>';

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
