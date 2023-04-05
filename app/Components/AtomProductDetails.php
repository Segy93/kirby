<?php

namespace App\Components;

use App\Providers\CategoryService;


/**
* Koristi se za kratke prikaze produkata (sadrze sliku cenu i tekst)
*/
class AtomProductDetails extends BaseComponent {
    protected $composite = true;
    protected $css       = ['AtomProductDetails/css/AtomProductDetails.css'];




    public function renderHTML($details = null) {
        $args = [
            'details'       => $details,
        ];
        return view('AtomProductDetails/templates/AtomProductDetails', $args);
    }
}
