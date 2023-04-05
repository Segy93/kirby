<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\ArticleService;

class ArticlePage extends BaseComponent {
    protected $composite = true;
    protected $css = [
        'ArticlePage/css/ArticlePage.css',
    ];

    protected $js   = [
        'ArticlePage/js/ArticlePage.js'
    ];

    protected $icons = [
        'ArticlePage/templates/icons',
    ];

    private $article_id             = 0;
    private $recomended_articles    = null;
    private $social_share_lg        = null;
    private $social_share_xs        = null;
    private $comment_list           = null;
    private $base_url               = '';

    public function __construct(
        $article_id,
        $recomended_articles,
        $social_share_lg = null,
        $social_share_xs = null,
        $comment_list = null
    ) {
        parent::__construct([$recomended_articles, $social_share_lg, $comment_list]);
        $this->article_id           = $article_id;
        $this->recomended_articles  = $recomended_articles;
        $this->social_share_xs      = $social_share_xs;
        $this->social_share_lg      = $social_share_lg;
        $this->comment_list         = $comment_list;
        $article                    = ArticleService::getByID($this->article_id);
        $views = ++ $article->views;
        $updates = [
            'views' => $views,
        ];
        ArticleService::update($this->article_id, $updates);
        $this->base_url             = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['SERVER_NAME'];
    }

    public function renderHTML() {
        $args = [
            'article'               => ArticleService::getByID($this->article_id),
            'tags'                  => ArticleService::getArticleTags($this->article_id),
            'recomended_articles'   => $this->recomended_articles,
            'social_share_xs'       => $this->social_share_xs,
            'social_share_lg'       => $this->social_share_lg,
            'comment_list'          => $this->comment_list,
            'base_url'              => $this->base_url,
        ];
        return  view('ArticlePage/templates/ArticlePage', $args);
    }
}
