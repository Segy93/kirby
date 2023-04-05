<?php

namespace App\Components;


/**
* Koristi se za prikaz imena proizvoda
*/
class AtomProductName extends BaseComponent {
    protected $css       = ['AtomProductName/css/AtomProductName.css'];

    public function renderHTML($name = '') {
        $args = [
            'name' => $name,
        ];
        return view('AtomProductName/templates/AtomProductName', $args);
    }
}
