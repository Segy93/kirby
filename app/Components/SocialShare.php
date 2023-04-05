<?php
namespace App\Components;

use App\Components\Basecomponent;
use App\Providers\SocialNetworkService;

class SocialShare extends Basecomponent {
    protected $css = [
        'SocialShare/css/social-share-kit.css',
        'SocialShare/css/SocialShare.css',
    ];

    protected $js  = [];

    private $shareable = true;


    public function __construct($shareable = true) {
        $this->shareable = $shareable;
        if ($this->shareable) {
            $this->js [] = 'SocialShare/js/social-share-kit.js';
            $this->js [] = 'SocialShare/js/SocialShare.js';
        }
    }

    public function renderHTML() {
        $args = [
            'networks' => SocialNetworkService::getShareOnSocialNetworks(),
        ];

        return view('SocialShare/templates/SocialShareLarge', $args);
    }
}
