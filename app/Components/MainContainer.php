<?php

namespace App\Components;

use App\Components\BaseComponent;
/**
*
*/
class MainContainer extends BaseComponent {
    protected $composite = true;
    protected $css        = ['MainContainer/css/MainContainer.css'];

    public function renderHTML() {
        return view('MainContainer/templates/MainContainer', [
            'banner'        => $this->children[0],
            'content'       => $this->children[1],
        ]);
    }
}
