<?php

namespace App\Components;

use App\Providers\ArticleCategoryService;

/**
*
*/
class FooterItNews extends BaseComponent {
    protected $css = ['FooterItNews/css/FooterItNews.css'];

    public function renderHTML() {
        $args = [
            'categories' => ArticleCategoryService::getAll(),
        ];

        return view('FooterItNews/templates/FooterItNews', $args);
    }
}
