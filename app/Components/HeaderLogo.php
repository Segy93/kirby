<?php

namespace App\Components;

/**
 * Logotip
 */
class HeaderLogo extends BaseComponent {
    // Koristi se za stampu, posto nema crne pozadine,
    // treba nam logotip sa crnim slovimaa
    private static $image_print = '/Components/HeaderLogo/img/logo.png';
    private static $image_view = '/Components/HeaderLogo/img/logo.png';
    private static $image_print_png = '/Components/HeaderLogo/img/logo.png';
    private static $image_view_png = '/Components/HeaderLogo/img/logo.png';
    private static $alt   = 'Kese za Kirby logo';
    private $print_only = false;
    protected $css = ['HeaderLogo/css/HeaderLogo.css'];

    public function __construct($print_only = false) {
        $this->print_only = $print_only;
    }

    public function renderHTML() {
        $is_ie = strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/7.0; rv:11.0') !== false;

        $args = [
            'alt'               => self::$alt,
            'image_view'        => $is_ie ? self::$image_view_png : self::$image_view,
            'image_print'       => $is_ie ? self::$image_print_png : self::$image_print,
            'print_only'        => $this->print_only,
        ];
        return view('HeaderLogo/templates/HeaderLogo', $args);
    }
}
