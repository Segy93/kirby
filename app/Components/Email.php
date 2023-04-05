<?php

namespace App\Components;

use App\Components\BaseComponent;

/**
*
*/
class Email extends BaseComponent {
    private $view;

    private $params = [];

    public function __construct($view = null, $params = null) {
        $this->view = !empty($view) ? $view : 'Default';

        if (!empty($params)) {
            $this->params = $params;
        }
    }

    /**
     * Renderuje HTML za email
     *
     * @return \Illuminate\View\View
     */
    public function renderHTML() {
        $content = view('Email/templates/' . $this->view, [
            'params'     => $this->params,
        ]);

        return view('Email/templates/email', [
            'content'   => $content,
        ]);
    }
}
