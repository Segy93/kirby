<?php

namespace App\Components;

use App\Components\BaseComponent;

/**
*
*/
class AdminHeading extends BaseComponent {
    private $title;

    public function __construct($title) {
        $this->title = $title;
    }

    public function renderHTML() {
        return view('AdminHeading/templates/AdminHeading', [
            'title' => $this->title
        ]);
    }
}
