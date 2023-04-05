<?php

namespace App\Providers;

use App\Providers\BaseService;

class SocialNetworkService extends BaseService {

    private static $social = [
        [
            'label' => 'Facebook',
            'name'  => 'facebook',
            'link'  => 'https://www.facebook.com/MonitorBeograd/',
        ],
        [
            'label' => 'Twitter',
            'name'  => 'twitter',
            'link'  => 'https://twitter.com/Monitor_System',
        ],
        [
            'label' => 'Pinterest',
            'name'  => 'pinterest',
            'link'  => 'https://www.pinterest.com//MonitorBeograd',
        ],
        [
            'label' => 'Instagram',
            'name'  => 'instagram',
            'link'  => 'https://www.instagram.com/monitor.rs/?hl=sr',
        ],
        [
            'label' => 'YouTube',
            'name'  => 'youtube',
            'link'  => 'https://www.youtube.com/channel/UCvVeIu2gZZl5il-srlzYaXQ',
        ],
    ];

    private static $social_shareable = [
        [
            'label' => 'Facebook',
            'name'  => 'facebook',
            'link'  => 'https://www.facebook.com/MonitorBeograd/',
        ],
        [
            'label' => 'Twitter',
            'name'  => 'twitter',
            'link'  => 'https://twitter.com/Monitor_System',
        ],
        [
            'label' => 'Pinterest',
            'name'  => 'pinterest',
            'link'  => 'https://www.pinterest.com//MonitorBeograd',
        ],
    ];
    public static function getSocialNetworks() {
        return self::$social;
    }

    public static function getShareOnSocialNetworks() {
        return self::$social_shareable;
    }
}
