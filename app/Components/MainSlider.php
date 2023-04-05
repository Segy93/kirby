<?php

namespace App\Components;

/**
 *
 */
class MainSlider extends BaseComponent {

    protected $composite  = true;
    protected $css        = ['MainSlider/css/MainSlider.css'];
    private $banners     = [];
    public function __construct($banners) {
        $this->banners = $banners;
        if ($banners) {
            parent::__construct([$banners]);
        }
    }

    public function renderHTML() {
        $args = [
            'banners' => $this->banners,
        ];
        return view('MainSlider/templates/MainSlider', $args);
    }
}
