<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\StaticPageService;
use App\Providers\PermissionService;
use App\Providers\SessionService;

/**
* Kreiranje i izmena statičnih strana
*/
class AdminStaticPage extends BaseComponent {
    protected $css       = ['AdminStaticPage/css/AdminStaticPage.css'];
    protected $js        = ['AdminArticles/libs/tinymce/tinymce.min.js'];

    private $page = null;
    public function __construct($page = null) {
        $this->page = $page === null ? null : StaticPageService::getPageById($page);
        if ($page === null && PermissionService::checkPermission('page_static_create')) {
            $this->js [] = 'AdminStaticPage/js/AdminStaticPage.js';
        } elseif (PermissionService::checkPermission('page_static_update')) {
            $this->js [] = 'AdminStaticPage/js/AdminStaticPageChange.js';
        }
    }

    public function renderHTML() {
        $args = [
            'permissions'   => [
                'page_static_create'   =>  PermissionService::checkPermission('page_static_create'),
                'page_static_read'     =>  PermissionService::checkPermission('page_static_read'),
                'page_static_update'   =>  PermissionService::checkPermission('page_static_update'),
            ],
            'page' => $this->page,
            'categories'    => $this->fetchCategories(),
            'csrf_field'    => SessionService::getCsrfField(),
        ];
        return view('AdminStaticPage/templates/AdminStaticPage', $args);
    }


    public function createPage($params) {
        $name       = $params ['name'];
        $category   = intval($params ['category']);
        $text       = $params ['text'];

        return StaticPageService::createPage($name, $category, $text);
    }

    public function fetchCategories() {
        return StaticPageService::getAllCategories();
    }
}
