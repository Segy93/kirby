<?php

namespace App\Components;

use App\Components\BaseComponent;

/**
*
*/
class Columns extends BaseComponent {
    protected $composite = true;
    protected $css       = ['Columns/css/Columns.css'];
    protected $bigger    = 0;
    protected $children  = [];

    public function __construct($bigger = 0, $children = []) {
        $this->bigger   = $bigger;
        $this->children = $children;
        parent::__construct($this->children);
    }

    public function renderHTML() {
        return view('Columns/templates/Columns', [
            'column_count'  => count($this->children),
            'columns'       => $this->children,
            'bigger'        => $this->bigger,
        ]);
    }
}
