<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\ArticleCategoryService;
use App\Providers\PermissionService;
use App\Providers\SessionService;



class AdminCategories extends BaseComponent{

    protected $js = [
        'AdminCategories/js/AdminCategoriesCreate.js',
        'AdminCategories/js/AdminCategoriesList.js',
    ];

    public function renderHTML() {
        $args = [
            'permissions'   => [
                'category_create'   =>  PermissionService::checkPermission('articleCategory_create'),
                'category_read'     =>  PermissionService::checkPermission('articleCategory_read'),
                'category_update'   =>  PermissionService::checkPermission('articleCategory_update'),
                'category_delete'   =>  PermissionService::checkPermission('articleCategory_delete'),
            ],
            'csrf_field'    => SessionService::getCsrfField(),
        ];
        return
            view('AdminCategories/templates/AdminCategoriesCreate', $args)
            . view('AdminCategories/templates/AdminCategoriesList', $args)
            . view('AdminCategories/templates/AdminCategoriesChange', $args)
            . view('AdminCategories/templates/AdminCategoriesDelete', $args)
        ;
    }

    public function getJSConfiguration() {
        return [
            'permission_reorder' => PermissionService::checkPermission('articleCategory_update'),
        ];
    }
    public function __construct() {
        if (PermissionService::checkPermission('articleCategory_update')) {
            $this->js[] = 'AdminCategories/js/AdminCategoriesChange.js';
        }

        if (PermissionService::checkPermission('articleCategory_delete')) {
            $this->js[] = 'AdminCategories/js/AdminCategoriesDelete.js';
        }
    }










    public function createCategory($params) {
        $name  = $params ['name'];
        $image = $params['image'];
        return ArticleCategoryService::create($name, $image);
    }











    public function isCategoryNameTaken($params) {
        $name = $params['name'];

        return ArticleCategoryService::isCategoryNameTaken($name);
    }

    public function fetchData() {
        return [
            'categories' => ArticleCategoryService::getAll(),
        ];
    }
        /**
     * Vraca kategoriju koja se trazi
     * @param  string $params['category_id'] ID kategorije cije podatke dobijamo
     * @return Boolean         vraca da li postoji ili ne
     */
    public function fetchCategory($params) {
        $category_id = intval($params['category_id']);

        return ArticleCategoryService::getByID($category_id);
    }











    public function updateImage($params) {
        $id     =   intval($params['category_id']);
        $image  =   $params['picture'];

        return ArticleCategoryService::update(
            $id,
            [
                'picture'   =>  $image,
            ]
        );
    }

    public function updateName($params) {
        $category_id = intval($params['category_id']);
        $name   = $params['name'];

        return ArticleCategoryService::update($category_id, [
            'name' => $name,
        ]);
    }
    /**
     * Promena redosleda predmeta
     * @param   int     $params['order_old']    Stara pozicija
     * @param   int     $params['order_new']    Nova pozicija
     * @return  int                             Vraca true ako ima dozvolu inace vraca false
     */
    public function changeOrder($params) {
        $order_old = intval($params['order_old']);
        $order_new = intval($params['order_new']);
        $category_id = ArticleCategoryService::getCategoryByOrder($order_old)->id;

        return ArticleCategoryService::reorder($category_id, $order_new);
    }









    public function deleteCategory($params) {
        $category_id = intval($params['category_id']);

        return ArticleCategoryService::delete($category_id);
    }
}
