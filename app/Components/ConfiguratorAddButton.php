<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\SessionService;
use Illuminate\Contracts\View\View;

/**
 * Dugme za dodavanje proizvoda u konfigurator
 */
class ConfiguratorAddButton extends BaseComponent {
    protected $css       = ['ConfiguratorAddButton/css/ConfiguratorAddButton.css'];
    protected $js        = ['ConfiguratorAddButton/js/ConfiguratorAddButton.js'];


    /**
     * Ispisivanje html-a
     *
     * @param   int  $product_id        Id proizvoda
     * @param   int  $configuration_id  Id konfiguracije
     *
     * @return  View
     */
    public function renderHTML(?int $product_id = null, ?int $configuration_id = null): View {
        $args = [
            'configuration_id'  => $configuration_id,
            'csrf_field'        => SessionService::getCsrfField(),
            'js_template'       => $product_id === null,
            'product_id'        => $product_id,
        ];
        return view('ConfiguratorAddButton/templates/ConfiguratorAddButton', $args);
    }
}
