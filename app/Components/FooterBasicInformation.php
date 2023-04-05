<?php

namespace App\Components;
/**
*
*/
class FooterBasicInformation extends BaseComponent {
    protected $css = ['FooterBasicInformation/css/FooterBasicInformation.css'];

    public function renderHTML($category = null) {
        $args = [
            'category' => $category
        ];
        return view('FooterBasicInformation/templates/FooterBasicInformation', $args);
    }
}
