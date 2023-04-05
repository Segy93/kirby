<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\CategoryService;
use App\Providers\UserService;

/**
 *
 */
class MainMenu extends BaseComponent {
    protected $css  = ['MainMenu/css/MainMenu.css'];
    protected $js   = ['MainMenu/js/MainMenu.js'];
    protected $icons = ['MainMenu/templates/icons'];

    private $expanded = false;

    public function __construct(bool $expanded) {
        $this->expanded = $expanded;
    }

    public function renderHTML() {
        $args = [
            'categories'    => CategoryService::getAllCategories(),
            'isLoggedIn'    => UserService::isUserLoggedIn(),
            'userId'        => UserService::getCurrentUserId(),
            'links'         => CategoryService::getCategoryTree(),
            'expanded'      => $this->expanded,
        ];

        return view('MainMenu/templates/MainMenu', $args);
    }
}
