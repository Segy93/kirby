<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\ArticleCategoryService;


/**
*
*/
class CategoryList extends BaseComponent {
    protected $css = ['CategoryList/css/CategoryList.css'];

    public function renderHTML() {
        $args = [
            'categories' => ArticleCategoryService::getAll(),
        ];

        return view('CategoryList/templates/CategoryList', $args);
    }
}
