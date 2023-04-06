<?php

namespace App\Http\Controllers;

use App\Providers\AdminService;
use App\Providers\BaseService;
use App\Providers\ConfigService;
use App\Providers\SEOService;
use App\Providers\SessionService;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Support\Facades\Request;
use Laravel\Lumen\Routing\Controller;

class BaseController extends Controller {


    protected $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;

        BaseService::setEntityManager($em);
        BaseService::checkCSRFToken();
    }

    protected $content = [
        'header'        => [],
        'navigation'    => [],
        'sidebar'       => [],
        'main'          => [],
        'footer'        => [],
    ];

    protected $css = [];
    protected $js  = [];

    protected $css_base = [
        //'libs/bootstrap/css/bootstrap.min.css',
        //'libs/bootstrap/css/bootstrap-theme.min.css',

        // Range slider
        'libs/nouislider.min.css',
        'libs/omni-slider.min.css',

        'libs/common_landings.css',
    ];

    protected $js_base = [
        //'libs/plugins/jQuery/jquery-2.2.3.min.js',
        //'libs/underscore-min.js',
        //'libs/bootstrap/js/bootstrap.min.js',

        // Range slider
        'libs/nouislider.min.js',
        'libs/omni-slider.min.js',
        'libs/underscore-min.js',

        'libs/KirbyPolyfill.js',
        'libs/KirbyMainAjax.js',
        'libs/KirbyMainDOM.js',
        'libs/KirbyMainRouter.js',
    ];

    protected function getJSExternal() {
        return [];
    }

    public function getCSS() {
        return array_merge($this->css_base, $this->css);
    }

    public function getJS() {
        return array_merge($this->js, $this->js_base);
    }

    public function getCsrfMeta() {
        return SessionService::getCsrfMeta();
    }

    public static function getCsrfField() {
        return SessionService::getCsrfField();
    }

    protected function pageHeader():void {
    }

    protected function pageFooter():void {
    }

    protected function pageMenu(string $page):void {
    }

    protected function getRequirements($page, $params = [], $additional = [], $url = [], $full_url = '') {
        $method = $page === '404' ? 'p404' : $page . 'Page';

        $this->pageHeader();
        $this->pageMenu($page);
        $this->$method($params, $additional, $url, $full_url);
        $this->pageFooter();
    }

    protected function getView($page, $params = [], $additional = [], $url = [], $full_url = '') {
        $this->getRequirements($page, $params, $additional, $url, $full_url);
        $search = array(
            '/(\s)+/',
            '~>\\s+<~m',
        );

        $replace = array(
            ' ',
            '><',
        );

        $can_upload = AdminService::isAdminLoggedIn();
        return preg_replace($search, $replace, view('layout', [
            'content'       => $this->content,
            'csrf_field'    => self::getCsrfField(),
            'csrf_meta'     => $this->getCsrfMeta(),
            'css_local'     => array_unique($this->getCSS()),
            'fm_key'        => $can_upload ? ConfigService::getFmKey() : '',
            'is_dev'        => env('APP_DEBUG') === true,
            'js_external'   => $this->getJSExternal(),
            'js_local'      => array_unique($this->getJS()),
            'seo'           => SEOService::getSEOByURL(Request::path()),
        ]));
    }
}
