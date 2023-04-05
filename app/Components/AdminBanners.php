<?php

namespace App\Components;

use App\Providers\BannerService;
use App\Providers\PermissionService;
use App\Providers\SessionService;

/**
*
*/
class AdminBanners extends BaseComponent {

    protected $js = [
        'AdminBanners/js/AdminBannersCreate.js',
        'AdminBanners/js/AdminBannersList.js',
        'AdminBanners/js/AdminBannersChange.js',
        'AdminBanners/js/AdminBannersDelete.js',
        'AdminBanners/js/bootstrap-select.min.js',
    ];

    protected $css = [
        'AdminBanners/css/bootstrap-select.min.css',
    ];

    public function renderHTML() {
        $args = [
            'permissions' => [
                'banner_create'       => PermissionService::checkPermission('banner_create'),
                'banner_read'         => PermissionService::checkPermission('banner_read'),
                'banner_update'       => PermissionService::checkPermission('banner_update'),
                'banner_delete'       => PermissionService::checkPermission('banner_delete'),
            ],
            'pages'         => BannerService::getPageTypes(),
            'csrf_field'    => SessionService::getCsrfField(),
        ];

        return
            view('AdminBanners/templates/AdminBannersCreate', $args)
            . view('AdminBanners/templates/AdminBannersList', $args)
            . view('AdminBanners/templates/AdminBannersChange', $args)
            . view('AdminBanners/templates/AdminBannersDelete', $args)
        ;
    }


    /* Create*/

    public function createBanner($params) {
        $name           = $params['name'];
        $position_id    = $params['position_id'];
        $image          = $params['image'];
        $link           = $params['link'];
        $url            = $params['url'];

        return BannerService::createBanner($position_id, $name, $image, $link, $url);
    }







    /*READ*/

    public function isNameTaken($params) {
        $name = $params['name'];

        return BannerService::isNameTaken($name);
    }

    public function fetchBanner($params) {
        $banner_id = $params['banner_id'];

        return BannerService::getBannerById($banner_id);
    }

    public function getPagePositions($params) {
        $page_id = $params['page_id'];

        return BannerService::getPostionsByPageTypeId($page_id);
    }

    public function getFilterData($params) {
        $page_id = $params['page_id'];
        return BannerService::getBannerPageFiltersPageID($page_id);
    }

    public function getCategoryFilters($params) {
        $machine_name = $params['machine_name'];
        $category_id  = $params['category_id'];
        return BannerService::getBannersFiltersByPageType($machine_name, $category_id);
    }








    public function fetchData($params) {
        $search         = $params['search'];
        $banner_id      = intval($params['banner_id']);
        $direction      = boolval($params['direction']);
        $limit          = intval($params['limit']);

        return [
            'banners' => BannerService::getAll($banner_id, $search, $direction, $limit),
            'pages'  =>  BannerService::getPageTypes(),
        ];
    }

    /*UPDATE*/

    public function changeStatus($params) {
        $banner_id = $params['banner_id'];
        $status    = $params['status'];

        return BannerService::updateBanner($banner_id, ['status' => $status]);
    }

    public function updateBanner($params) {
        $banner_id      = $params['banner_id'];
        $title          = $params['name'];
        $link           = $params['link'];
        $urls           = $params['url'];
        $position_id    = $params['position'];

        return BannerService::updateBanner($banner_id, [
            'title' => $title,
            'link' => $link,
            'urls' => $urls,
            'position_id' => $position_id
        ]);
    }

    public function changeImage($params) {
        $banner_id = $params['banner_id'];
        $image     = $params['image'];

        return BannerService::updateBanner($banner_id, ['image' => $image]);
    }

    /*DELETE*/

    public function deleteBanner($params) {
        $banner_id = $params['banner_id'];

        return BannerService::deleteBanner($banner_id);
    }
}
