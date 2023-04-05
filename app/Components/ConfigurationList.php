<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\ConfigurationService;
use App\Providers\SessionService;
use App\Providers\UserService;

/**
 *  Lista konfiguracija za korisnika
 */
class ConfigurationList extends BaseComponent {
    protected $css       = [
        'ConfigurationList/css/ConfigurationList.css',
        'libs/plugins/sweetalert/sweetalert2.min.css',
    ];
    protected $js        = [
        'ConfigurationList/js/ConfigurationList.js',
        'libs/plugins/sweetalert/sweetalert2.min.js',
    ];

    public function renderHTML() {
        $args = [
            'username'       => UserService::getCurrentUser()->username,
            'configurations' => ConfigurationService::getConfigurationsForCurrentUser(),
            'csrf_field'     => SessionService::getCsrfField(),
        ];

        return view('ConfigurationList/templates/ConfigurationList', $args);
    }

    /**
     * Brisanje konfiguracije
     *
     * @param array $params                       Niz parametara
     *  param int   $params['configuration_id']   Id konfiguracije
     * @return void
     */
    public function deleteConfiguration(array $params): void {
        $configuration_id = intval($params['configuration_id']);
        ConfigurationService::deleteConfiguration($configuration_id);
    }
}
