<?php

namespace App\Components;

use App\Providers\StaticPageService;

/**
*
*/
class Footer extends BaseComponent {

    protected $composite                  = true;
    protected $css                        = ['Footer/css/Footer.css'];
    private $basic_information  = null;
    private $services           = null;
    private $news               = null;
    private $contact            = null;
    private $worktime           = null;
    private $social_share_lg    = null;
    private $info               = null;


    public function __construct(
        $basic_information = null,
        $services = null,
        $news = null,
        $contact = null,
        $worktime = null,
        $social_share_lg = null,
        $info = null
    ) {
        $construct = [];
        if ($basic_information) {
            $construct [] = $basic_information;
        }

        if ($services) {
            $construct [] = $services;
        }

        if ($news) {
            $construct [] = $news;
        }

        if ($contact) {
            $construct [] = $contact;
        }

        if ($worktime) {
            $construct [] = $worktime;
        }

        if ($social_share_lg) {
            $construct [] = $social_share_lg;
        }

        if ($info) {
            $construct [] = $info;
        }

        parent::__construct($construct);
        $this->basic_information    = $basic_information;
        $this->services             = $services;
        $this->news                 = $news;
        $this->contact              = $contact;
        $this->worktime             = $worktime;
        $this->social_share_lg      = $social_share_lg;
        $this->info                 = $info;
    }

    public function renderHTML() {
        $args = [
            'basic_information'     =>  $this->basic_information,
            'services'              =>  $this->services,
            'news'                  =>  $this->news,
            'contact'               =>  $this->contact,
            'worktime'              =>  $this->worktime,
            'social_share_lg'       =>  $this->social_share_lg,
            'info'                  =>  $this->info,
            'static_categories'     =>  StaticPageService::getAllCategories(),
        ];
        return view('Footer/templates/Footer', $args);
    }
}
