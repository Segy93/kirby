<?php

namespace App\Components;
use App\Providers\PermissionService;
use App\Providers\SEOService;
use App\Providers\SessionService;

class AdminSEO extends BaseComponent {
    private $machine_name;
    private $show_form = 'both'; // Parametar za odabir prikaza vrednosti: both, create,update

    public function __construct($machine_name = null, $show_form = 'both') {
        $this->machine_name = $machine_name;
        $this->show_form    = $show_form;
    }

    public function renderHTML() {
        return view('AdminSEO/templates/AdminSEO', [
            'permissions' => [
                'seo_create' => PermissionService::checkPermission($this->machine_name . '_create'),
                'seo_update' => PermissionService::checkPermission($this->machine_name . '_update'),
                'seo_delete' => PermissionService::checkPermission($this->machine_name . '_delete'),
            ],
            'csrf_field'    => SessionService::getCsrfField(),
            'show_form'     => $this->show_form,
        ]);
    }

    public function getCSS() {
        return [
            'AdminSEO/css/AdminSEO.css'
        ];
    }

    public function getJS() {
        return [
            'AdminSEO/js/AdminSEO.js'
        ];
    }










    public function createEntry($params) {
        $machine_name   =   $params['machine_name'];
        $url            =   $params['url'];
        $keywords       =   $params['keywords'];
        $description    =   $params['description'];
        $title          =   $params['title'];
        $picture        =   array_key_exists('picture', $params) ? $params['picture'] : null;

        $response = SEOService::createSEO(
            $machine_name,
            $url,
            $keywords,
            $description,
            $title,
            $picture,
            true
        );

        return $response;
    }










    public function fetchData($params) {
        return SEOService::getSEO($params['machine_name']);
    }


    public function isUrlTaken($params) {
        $url = $params['url'];
        return SEOService::isUrlTaken($url);
    }






    public function updateSEO($params) {
        return SEOService::updateSEO($params['machine_name'], $params);
    }
}
