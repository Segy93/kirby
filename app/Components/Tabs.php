<?php

namespace App\Components;

use App\Components\BaseComponent;

/**
 *
 */
class Tabs extends BaseComponent {
    protected $composite = true;
    protected $css = ['Tabs/css/Tabs.css'];

    private $contents = [];
    private $active   = '';

    public function __construct($tabs = [], $active = '') {
        if ($active === '') {
            $active = $tabs[0]['label'];
        }

        $this->active = $active;
        foreach ($tabs as $tab) {
            array_push($this->children, $tab['component']);
        }

        $this->tabs = $tabs;
    }

    public function renderHTML($children_args = []) {
        return view('Tabs/templates/Tabs', [
            'children_args' => $children_args,
            'tabs'          => $this->tabs,
            'active'        => $this->active,
        ]);
    }
}
