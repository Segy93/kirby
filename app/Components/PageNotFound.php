<?php

namespace App\Components;

use App\Components\BaseComponent;

/**
 *
 */
class PageNotFound extends BaseComponent {
    protected $composite = true;
    protected $css       = ['PageNotFound/css/PageNotFound.css'];
    protected $js        = ['PageNotFound/js/PageNotFound.js'];


    public function renderHTML() {
        return view('PageNotFound/templates/PageNotFound');
    }
}
