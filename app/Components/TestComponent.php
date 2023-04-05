<?php

namespace App\Components;

use App\Components\BaseComponent;

/**
 *
 */
class TestComponent extends BaseComponent {
    public function renderHTML() {
        return view('TestComponent/templates/TestComponent');
    }
}
