<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\StaticPageService;
use App\Providers\PermissionService;
use App\Providers\SessionService;



class AdminStaticPages extends BaseComponent{
    protected $js = [
        //'AdminStaticPages/js/AdminStaticPagesCreate.js',
        'AdminStaticPages/js/AdminStaticPagesList.js',
        'AdminArticles/libs/tinymce/tinymce.min.js',
    ];

    public function __construct() {
        if (PermissionService::checkPermission('page_static_delete')) {
            $this->js[] = 'AdminStaticPages/js/AdminStaticPagesDelete.js';
        }
    }

    public function renderHTML() {
        $args = [
            'permissions'   => [
                'page_static_create'   =>  PermissionService::checkPermission('page_static_create'),
                'page_static_read'     =>  PermissionService::checkPermission('page_static_read'),
                'page_static_update'   =>  PermissionService::checkPermission('page_static_update'),
                'page_static_delete'   =>  PermissionService::checkPermission('page_static_delete'),
            ],
            'categories'    => $this->fetchCategories(),
            'csrf_field'    => SessionService::getCsrfField(),
        ];
        return
            view('AdminStaticPages/templates/AdminStaticPagesList', $args)
            . view('AdminStaticPages/templates/AdminStaticPagesDelete', $args)
        ;
    }

    public function getJSConfiguration() {
        return [
            'permission_reorder' => PermissionService::checkPermission('page_static_update'),
        ];
    }










    public function createPage($params) {
        $name       = $params ['name'];
        $category   = intval($params ['category']);
        $text       = $params ['text'];

        return StaticPageService::createPage($name, $category, $text);
    }








    public function updatePage($params) {
        $page_id        = intval($params['page_id']);
        $name           = $params['name'];
        $category_id    = $params['category_id'];
        $text           = $params['text'];

        return StaticPageService::updatePage($page_id, [
            'title'         => $name,
            'category_id'   => $category_id,
            'text'          => $text,
        ]);
    }





    public function isPageNameTaken($params) {
        $name = $params['name'];

        return StaticPageService::isPageNameTaken($name);
    }

    public function fetchData() {
        return [
            'pages' => StaticPageService::getAllPages(),
        ];
    }

    public function fetchCategories() {
        return StaticPageService::getAllCategories();
    }

        /**
     * Vraca kategoriju koja se trazi
     * @param  string $params['page_id'] ID kategorije cije podatke dobijamo
     * @return Boolean         vraca da li postoji ili ne
     */
    public function fetchPage($params) {
        $page_id = intval($params['page_id']);

        return StaticPageService::getPageById($page_id);
    }
















    public function deletePage($params) {
        $page_id = intval($params['page_id']);

        return StaticPageService::deletePage($page_id);
    }
}
