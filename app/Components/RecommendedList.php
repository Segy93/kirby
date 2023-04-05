<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\ArticleService;

/**
 *
 */
class RecommendedList extends BaseComponent {
    protected $css = ['RecommendedList/css/RecommendedList.css'];
    protected $icons = ['RecommendedList/templates/icons'];

    public function renderHTML() {
        $args = [
            'popular_articles' => ArticleService::getByViews(6),
        ];

        return view('RecommendedList/templates/RecommendedList', $args);
    }
}
