<?php

namespace App\Components;

use App\Providers\PermissionService;

/**
*
*/
class AdminSidebar extends BaseComponent {
    protected $css = [
        'AdminSidebar/css/AdminSidebar.css',
    ];

    private $page = '';
    private static $select_override = [
        'Adrese korisnika' => 'korisnici',
    ];

    public function __construct($page) {
        $this->page = $page;
    }

    private function getSelected() {
        if (array_key_exists($this->page, self::$select_override)) {
            return self::$select_override[$this->page];
        } else {
            return $this->page;
        }
    }

    public function renderHTML() {
        return view('AdminSidebar/templates/AdminSidebar', [
            'page'  => $this->page,
            'links' => $this->getLinks(),
            'selected' => $this->getSelected(),
        ]);
    }










    private function getLinks() {
        $links = [];

        if (PermissionService::checkPermission('admin_read')
            || PermissionService::checkPermission('admin_create')
        ) {
            $links[] = [
                'url'       => 'administratori',
                'title'     => 'Administratori',
            ];
        }

        if (PermissionService::checkPermission('permission_read')
            || PermissionService::checkPermission('permission_create')
        ) {
            $links[] = [
                'url'       => 'dozvole',
                'title'     => 'Dozvole',
            ];
        }





        if (PermissionService::checkPermission('banner_read')) {
            $links[] = [
                'url'       => 'baneri',
                'title'     => 'Baneri',
            ];
        }





        if (PermissionService::checkPermission('user_read')) {
            $links[] = [
                'url'       => 'korisnici',
                'title'     => 'Korisnici',
            ];
        }

        if (PermissionService::checkPermission('order_read')) {
            $links[] = [
                'url'       => 'narudzbine',
                'title'     => 'Narudžbine',
            ];
        }

        if (PermissionService::checkPermission('comment_read')) {
            $links[] = [
                'url'       => 'komentari',
                'title'     => 'Komentari',
            ];
        }





        if (PermissionService::checkPermission('article_read')) {
            $links[] = [
                'url'       => 'clanci',
                'title'     => 'Članci',
            ];
        }

        if (PermissionService::checkPermission('tag_read') || PermissionService::checkPermission('tag_create')) {
            $links[] = [
                'url'       => 'tagovi',
                'title'     => 'Tagovi',
            ];
        }

        if (PermissionService::checkPermission('articleCategory_read')
            || PermissionService::checkPermission('articleCategory_read')
        ) {
            $links[] = [
                'url'       => 'kategorije',
                'title'     => 'Kategorije',
            ];
        }





        if (PermissionService::checkPermission('category_static_read')
            || PermissionService::checkPermission('category_static_read')
        ) {
            $links[] = [
                'url'       => 'staticne/kategorije',
                'title'     => 'Statične kategorije',
            ];
        }

        if (PermissionService::checkPermission('page_static_read')
            || PermissionService::checkPermission('page_static_read')
        ) {
            $links[] = [
                'url'       => 'staticne/strane',
                'title'     => 'Statične strane',
            ];
        }

        return $links;
    }
}
