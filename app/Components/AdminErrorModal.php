<?php

namespace App\Components;

use App\Components\BaseComponent;

/**
*
*/
class AdminErrorModal extends BaseComponent {
    protected $js = [
        'AdminErrorModal/js/AdminErrorModal.js',
    ];

    public function renderHTML() {
        return view('AdminErrorModal/templates/AdminErrorModal');
    }
}
