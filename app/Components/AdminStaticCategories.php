<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\StaticPageService;
use App\Providers\PermissionService;
use App\Providers\SessionService;



class AdminStaticCategories extends BaseComponent{
    protected $js = [
        'AdminStaticCategories/js/AdminStaticCategoriesCreate.js',
        'AdminStaticCategories/js/AdminStaticCategoriesList.js',
    ];

    public function renderHTML() {
        $args = [
            'permissions' => [
                'category_static_create'   =>  PermissionService::checkPermission('category_static_create'),
                'category_static_read'     =>  PermissionService::checkPermission('category_static_read'),
                'category_static_update'   =>  PermissionService::checkPermission('category_static_update'),
                'category_static_delete'   =>  PermissionService::checkPermission('category_static_delete'),
            ],
            'csrf_field' => SessionService::getCsrfField(),
        ];
        return
            view('AdminStaticCategories/templates/AdminStaticCategoriesCreate', $args)
            . view('AdminStaticCategories/templates/AdminStaticCategoriesList', $args)
            . view('AdminStaticCategories/templates/AdminStaticCategoriesChange', $args)
            . view('AdminStaticCategories/templates/AdminStaticCategoriesDelete', $args)
        ;
    }

    public function getJSConfiguration() {
        return [
            'permission_reorder'     => PermissionService::checkPermission('category_static_update'),
        ];
    }
    public function __construct() {
        if (PermissionService::checkPermission('category_static_update')) {
            $this->js[] = 'AdminStaticCategories/js/AdminStaticCategoriesChange.js';
        }

        if (PermissionService::checkPermission('category_static_delete')) {
            $this->js[] = 'AdminStaticCategories/js/AdminStaticCategoriesDelete.js';
        }
    }










    public function createCategory($params) {
        $name  = $params ['name'];

        return StaticPageService::createCategory($name);
    }








    public function updateName($params) {
        $category_id = intval($params['category_id']);
        $name   = $params['name'];

        return StaticPageService::updateCategory($category_id, [
            'name' => $name,
        ]);
    }





    public function isCategoryNameTaken($params) {
        $name = $params['name'];

        return StaticPageService::isCategoryNameTaken($name);
    }

    public function fetchData() {
        return [
            'categories' => StaticPageService::getAllCategories(),
        ];
    }

    /**
     * Vraca kategoriju koja se trazi
     * @param  string $params['category_id'] ID kategorije cije podatke dobijamo
     * @return Boolean         vraca da li postoji ili ne
     */
    public function fetchCategory($params) {
        $category_id = intval($params['category_id']);

        return StaticPageService::getCategoryById($category_id);
    }
















    public function deleteCategory($params) {
        $category_id = intval($params['category_id']);

        return StaticPageService::deleteCategory($category_id);
    }
}
