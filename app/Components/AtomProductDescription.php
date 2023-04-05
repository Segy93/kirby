<?php

namespace App\Components;


/**
* Koristi se za opis proizvoda
*/
class AtomProductDescription extends BaseComponent {
    protected $css       = ['AtomProductDescription/css/AtomProductDescription.css'];

    public function renderHTML($description = '') {
        $args = [
            'description' => $description,
        ];
        return view('AtomProductDescription/templates/AtomProductDescription', $args);
    }
}
