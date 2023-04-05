<?php

namespace App\Components;

use App\Components\BaseComponent;

/**
 *
 */
class Panes extends BaseComponent {
    protected $composite = true;
    protected $css       = ['Panes/css/Panes.css'];

    public function renderHTML() {
        return view('Panes/templates/Panes', [
            'pane_count' => count($this->children),
            'panes' => $this->children,
        ]);
    }
}
