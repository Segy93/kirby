<?php

namespace App\Components;

use App\Components\BaseComponent;

/**
 * Komponeta za info telefon o proizvodima
 */
class InfoHelpDesk extends BaseComponent {
    protected $css       = ['InfoHelpDesk/css/InfoHelpDesk.css'];

    public function renderHTML() {
        $args = [
        ];
        return view('InfoHelpDesk/templates/InfoHelpDesk', $args);
    }
}
