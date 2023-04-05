<?php

namespace App\Components;

/**
 *
 */
class AdminHeader extends BaseComponent {
    protected $css = ['AdminHeader/css/AdminHeader.css'];

    public function renderHTML() {
         return view('AdminHeader/templates/AdminHeader');
    }
}
