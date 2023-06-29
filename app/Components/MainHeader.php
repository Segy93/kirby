<?php

namespace App\Components;

use App\Components\BaseComponent;

/**
 * h1 element
 */
class MainHeader extends BaseComponent {
    protected $css  = ['MainHeader/css/MainHeader.css'];
    protected $js   = ['MainHeader/js/MainHeader.js'];

    private $heading = 'Kirby servis <br/> <span class = "main_header__small">kese, rezervni delovi, dodatna oprema i kompletan aparat <br/> Beograd, Banovo Brdo, Čukarica, <a href = "tel:+38163223242">063/22-32-42</a></span>';

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
