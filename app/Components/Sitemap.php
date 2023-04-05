<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\ArticleCategoryService;
use App\Providers\ArticleService;
use App\Providers\CategoryService;
use App\Providers\SEOService;
use App\Providers\TagService;

/**
 * Sitemap.xml
 */
class Sitemap extends BaseComponent {
    private static $priorities = [
        'articles'              => 0.5,
        'article_categories'    => 0.6,
        'article_tags'          => 0.6,
        'product_categories'    => 0.8,
        'products'              => 1,
        'static_pages'          => 0.3,
    ];

    private static $changefreq = [
        'articles'              => 'daily',
        'article_categories'    => 'weekly',
        'article_tags'          => 'weekly',
        'product_categories'    => 'daily',
        'products'              => 'daily',
        'static_pages'          => 'yearly',
    ];

    public function renderHTML() {
        $args = [
            'articles'              => ArticleService::getAll(),
            'categories'            => ArticleCategoryService::getAll(),
            'product_categories'    => CategoryService::getAllCategories(),
            'products'              => SEOService::getProductSEO(),
            'static_pages'          => SEOService::getStaticPageSEO(),
            'tags'                  => TagService::getAll(),
            'base_url'              => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['SERVER_NAME'],

            'priorities'            => self::$priorities,
            'changefreq'            => self::$changefreq,
        ];
        return view('Sitemap/templates/Sitemap', $args);
    }
}
