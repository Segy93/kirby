<?php

namespace App\Components;

/**
*
*/
class Header extends BaseComponent {

    protected $composite                = true;
    protected $css                      = ['Header/css/Header.css'];
    private $header_logo                = null;
    private $user_menu                  = null;
    private $search                     = null;
    private $company_info               = null;

// $user_menu, $search su null jer nisu potrebni kada se header poziva u adminu kontroleru zbog stampanja

    public function __construct($header_logo = null, $company_info = null, $user_menu = null, $search = null) {
        $components = [];

        if ($header_logo) {
            array_push($components, $header_logo);
        }

        if ($user_menu) {
            array_push($components, $user_menu);
        }

        if ($search) {
            array_push($components, $search);
        }

        if ($company_info) {
            array_push($components, $company_info);
        }

        $this->header_logo              = $header_logo;
        $this->user_menu                = $user_menu;
        $this->search                   = $search;
        $this->company_info             = $company_info;

        parent::__construct($components);
    }

    public function renderHTML() {
        $args = [
            'user_menu'                 =>  $this->user_menu,
            'header_logo'               =>  $this->header_logo,
            'search'                    =>  $this->search,
            'company_info'              =>  $this->company_info,
        ];
        return view('Header/templates/Header', $args);
    }
}
