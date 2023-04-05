<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\SessionService;

/**
 *
 */
class SettingsFilters extends BaseComponent {
    protected $composite = true;
    protected $css       = ['SettingsFilters/css/SettingsFilters.css'];
    protected $js        = ['SettingsFilters/js/SettingsFilters.js'];


    public function renderHTML() {
        $args = [
            'sort' => [
                'artid_desc'    => 'Datum opadajući',
                'artid_asc'     => 'Datum rastući',
                'price_asc'     => 'Cena rastuća',
                'price_desc'    => 'Cena opadajuća',
                'name_asc'      => 'Naziv rastući',
                'name_desc'     => 'Naziv opadajući',
            ],

            'csrf_field'    => SessionService::getCsrfField(),
        ];

        return view('SettingsFilters/templates/SettingsFilters', $args);
    }
}
