<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\ArticleService;

class ArticleRecommended extends BaseComponent {
    protected $composite    = true;
    protected $css          = ['ArticleRecommended/css/ArticleRecommended.css'];
    protected $icons        = ['ArticleRecommended/templates/icons'];
    protected $article_id   = null;
    protected $category_id  = null;
    protected $social_share = null;

    public function __construct($article_id, $social_share) {
        parent::__construct([$social_share]);
        $this->article_id = $article_id;

        $article = ArticleService::getByID($this->article_id);
        if (get_class($article) === 'Exception') {
            throw new \Exception($article->getMessage(), $article->getCode());
        }

        $this->category_id = $article->category_id;
        $this->social_share = $social_share;
    }

    public function renderHTML() {
        $args = [
            'recommended_articles'  => ArticleService::getRecommendedArticles($this->article_id),
            'social_share'          => $this->social_share,
        ];
        return view('ArticleRecommended/templates/ArticleRecommended', $args);
    }
}
