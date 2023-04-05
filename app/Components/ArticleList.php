<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\ArticleCategoryService;
use App\Providers\ArticleService;
use App\Providers\TagService;
use App\Providers\AdminService;

/**
*
*/
class ArticleList extends BaseComponent {
    protected $composite = true;
    protected $css = ['ArticleList/css/ArticleList.css'];
    protected $js = [
        'libs/underscore-min.js',
        'ArticleList/js/ArticleList.js',
    ];
    protected $js_config = [];





    private $id = 0;
    private $article_single = null;

    private $base_url = '';
    private $published_at = null;
    private $type = null;
    private $direction = true;
    private $limit = 5;
    private $username = '';

    private $newest = null;
    private $oldest = null;










    public function __construct(
        $id = 0,
        $type = null,
        $article_single = null,
        $published_at = null,
        $direction = 'Napred'
    ) {
        if ($article_single) {
            parent::__construct([$article_single]);
        }

        if ($type === 'author') {
            // U ovom slucaju je id username uradio sam ovako da ne budzim previse.
            $this->username = $id;
        } else {
            $this->id = intval($id);
        }
        $this->article_single = $article_single;
        $this->type = $type;
        $this->published_at = $published_at;
        $this->direction = $direction === 'Nazad' ? false : true;
        $base_url = '';
        if ($this->type === 'category') {
            $this->articles = ArticleService::getByCategoryID(
                $this->id,
                $this->published_at,
                $this->limit,
                $this->direction
            );

            $article_oldest = ArticleService::getByCategoryID(
                $this->id,
                null,
                1,
                false
            );

            $this->oldest = !empty($article_oldest) ? $article_oldest[0] : null;

            $article_newest = ArticleService::getByCategoryID(
                $this->id,
                null,
                1,
                true
            );

            $this->newest = !empty($article_newest) ? $article_newest[0] : null;

            $base_url = ArticleCategoryService::getByID($id)->url;
        } elseif ($type === 'tag') {
            $this->articles = ArticleService::getByTagID(
                $this->published_at,
                $this->limit/*+1*/,
                $this->id,
                $this->direction
            );

            $tag_oldest = ArticleService::getByTagID(
                null,
                1,
                $this->id,
                false
            );
            $this->oldest = !empty($tag_oldest) ? $tag_oldest[0] : null;

            $tag_newest = ArticleService::getByTagID(
                null,
                1,
                $this->id,
                true
            );
            $this->newest = !empty($tag_newest) ? $tag_newest[0] : null;
            $base_url = TagService::getByID($id)->url;
        } elseif ($type === 'author') {
            $author = AdminService::getAuthor($this->username);
            $id = $author->id;
            $this->articles = ArticleService::getByAuthorID(
                $this->published_at,
                $this->limit/*+1*/,
                $id,
                $this->direction
            );

            $author_oldest = ArticleService::getByAuthorID(
                null,
                1,
                $id,
                false
            );
            $this->oldest = !empty($author_oldest) ? $author_oldest[0] : null;

            $author_newest = ArticleService::getByAuthorID(
                null,
                1,
                $id,
                true
            );
            $this->newest = !empty($author_newest) ? $author_newest[0] : null;
            $base_url = 'autori/' . $author->username;
        }
        $this->base_url = '/' . $base_url . '/';
    }

    public function getJSConfiguration() {
        if (empty($this->articles)) {
            $date_first = null;
            $date_last  = null;
        } else {
            $date_first = $this->articles[0]->published_at;
            $date_last  = end($this->articles)->published_at;
        }

        return [
            'base_url'      => $this->base_url,
            'date_first'    => $date_first,
            'date_last'     => $date_last,
            'id_oldest'     => $this->oldest ? $this->oldest->id : null,
            'id_newest'     => $this->newest ? $this->newest->id : null,
            'id_object'     => $this->id,
            'type'          => $this->type,
        ];
    }










    public function fetchData($params) {
        $date       = urldecode($params['date_start']);
        if (empty($params['limit'])) {
            $limit = $this->limit;
        } else {
            $limit = intval($params['limit']);
        }

        $id         = intval($params['id_object']);
        $direction  = boolval($params['direction']);
        $type       = $params['type'];
        $with_author = boolval($params['with_author']);

        if ($type === 'category') {
            $articles = ArticleService::getByCategoryID(
                $id,
                $date,
                $limit,
                $direction,
                $with_author
            );
        } elseif ($type === 'tag') {
            $articles = ArticleService::getByTagID(
                $date,
                $limit,
                $id,
                $direction,
                $with_author
            );
        } elseif ($type === 'author') {
            $articles = ArticleService::getByAuthorID(
                $date,
                $limit,
                $id,
                $direction,
                $with_author
            );
        }
        return array_values($articles);
    }










    public function renderHTML() {
        $articles   = $this->articles;
        $exist      = empty($articles) === false;

        $first      = $exist ? $articles[0]     :   null;
        $last       = $exist ? end($articles)   :   null;

        $id_first   = $exist ? $first->id : null;
        $id_last    = $exist ? $last->id : null;

        $date_first = $exist ? $first->published_at : null;
        $date_last  = $exist ? $last->published_at : null;

        $id_newest  = $this->newest === null ? null : $this->newest->id;
        $id_oldest  = $this->oldest === null ? null : $this->oldest->id;

        if ($this->type === 'tag') {
            $node = TagService::getByID($this->id);
            $title = $node->name;
        } elseif ($this->type === 'category') {
            $node = ArticleCategoryService::getByID($this->id);
            $title = $node->name;
        } elseif ($this->type === 'author') {
            $node = AdminService::getAuthor($this->username);
            $title = $node->username;
        }
        $args = [
            'articles'          => $articles,
            'base_url'          => $this->base_url,
            'article_single'    => $this->article_single,

            'date_first'        => $date_first,
            'date_last'         => $date_last,
            'type'              => $this->type,
            'node'              => $node,
            'title'             => $title,

            'more_backward'     => $id_first !== $id_newest,
            'more_forward'      => $id_last !== $id_oldest,

            'direction'         => $this->direction,
        ];

        return view('ArticleList/templates/ArticleList', $args);
    }
}
