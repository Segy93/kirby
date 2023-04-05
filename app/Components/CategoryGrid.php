<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\CategoryService;

/**
 * Mreza kategorija
 */
class CategoryGrid extends BaseComponent {
    protected $composite = true;
    protected $css       = ['CategoryGrid/css/CategoryGrid.css'];
    protected $js        = ['CategoryGrid/js/CategoryGrid.js'];

    private $categories = [];

    public function __construct($parent = null) {
        if ($parent === null) {
            $this->categories = CategoryService::getCategoriesPromoted();
        } else {
            $this->categories = CategoryService::getCategorySubtree($parent);
        }
    }

    public function renderHTML() {
        $args = [
            'categories' => $this->categories,
        ];

        return view('CategoryGrid/templates/CategoryGrid', $args);
    }
}
