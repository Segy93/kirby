<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\SocialNetworkService;

/**
 *
 */
class SocialList extends BaseComponent {
    protected $css = [
        'SocialList/css/social-share-kit.css',
        'SocialList/css/SocialList.css',
    ];
    protected $box = null;

    public function renderHTML() {
        $args = [
            'social_items' => SocialNetworkService::getSocialNetworks(),
        ];

        return view('SocialList/templates/SocialList', $args);
    }
}
