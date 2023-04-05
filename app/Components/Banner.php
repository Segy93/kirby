<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\BannerService;

/**
*
*/
class Banner extends BaseComponent {
    protected $composite        = true;
    protected $css              = ['Banner/css/Banner.css'];
    protected $js               = ['Banner/js/Banner.js'];
    private $banners          = [];
    private $position_name    = '';
    private $position_id      = null;
    private $url              = null;
    private $type             = '';
    private $nr_banners       = 1;

    public function __construct($position_name = '', $url = null, $type = '', $nr_banners = 1) {
        $position               = BannerService::getPositionByName($position_name);
        $this->position_name    = $position_name;
        if ($position_name !== '') {
            $this->position_id      = $position->id;
        }
        $this->url              = $url;
        $this->type             = $type;
        $this->nr_banners       = $nr_banners;
        if ($position_name === 'Slajder') {
            $this->css [] = 'Banner/css/BannerSlider.css';
        }
    }

    public function renderHTML($js_template = false) {

        if ($js_template === false) {
            $this->banners = BannerService::getBannersByUrl(
                $this->position_id,
                $this->url,
                $this->type,
                $this->nr_banners,
                true
            );

            $nr_banners = count($this->banners);
        } else {
            $nr_banners = 0;
        }
        $args = [
            'count'             => $nr_banners,
            'js_template'       => $js_template,
            'banners'           => $this->banners,
        ];
        if ($js_template === false && empty($this->banners)) {
            return '';
        } elseif ($this->position_name === 'Slajder') {
            return view('Banner/templates/SliderBanner', $args);
        } else {
            return view('Banner/templates/Banner', $args);
        }
    }


    public function clickBanner($params) {
        $banner_id = intval($params['id']);
        return BannerService::updateBannerNrClicks($banner_id);
    }
}
