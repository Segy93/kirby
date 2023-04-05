<?php

namespace App\Components;

/**
 *
 */
class MenuSliderBox extends BaseComponent {

    protected $composite  = true;
    protected $css        = ['MenuSliderBox/css/MenuSliderBox.css'];
    private $main_menu  = null;
    private $slider     = null;

    private $expanded_menu = false;
    private $show_slider = false;

    public function __construct(
        $main_menu = null,
        $slider = null,
        $expanded_menu = null
    ) {
        $components = [];
        if ($main_menu !== null) {
            array_push($components, $main_menu);
        }

        if ($slider !== null) {
            array_push($components, $slider);
        }

        parent::__construct($components);

        $this->main_menu    = $main_menu;
        $this->slider       = $slider;
        $this->expanded_menu = $expanded_menu;

        $this->show_slider = $slider !== null;
    }

    public function renderHTML() {
        $args = [
            'main_menu'     => $this->main_menu,
            'slider'        => $this->slider,
            'show_slider'   => $this->show_slider,
            'expanded_menu' => $this->expanded_menu,
        ];

        return view('MenuSliderBox/templates/MenuSliderBox', $args);
    }
}
