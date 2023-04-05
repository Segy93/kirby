<?php

namespace App\Components;

use App\Components\BaseComponent;

/**
*
*/
class AtomBoxWrap extends BaseComponent {
    protected $css       = ['AtomBoxWrap/css/AtomBoxWrap.css'];

    public function renderHTML($top = '', $content = '', $bottom = '') {
        return view('AtomBoxWrap/templates/AtomBoxWrap', [
            'top' => $top,
            'content' => $content,
            'bottom' => $bottom,
        ]);
    }
}
