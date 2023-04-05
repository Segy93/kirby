<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\NotificationService;
use App\Providers\UserService;
use App\Providers\DeviceService;

/**
 *
 */
class NotificationsAllow extends BaseComponent {
    protected $composite = true;
    protected $css       = ['NotificationsAllow/css/NotificationsAllow.css'];
    protected $js        = ['NotificationsAllow/js/NotificationsAllow.js'];
    protected $js_config = [];

    public function __construct() {
        $device_subscribed               = false;
        $endpoints                       = NotificationService::getUserEndpoints();
        $device_name                     = DeviceService::getDeviceInfo();
        foreach ($endpoints as $endpoint) {
            if ($device_name === $endpoint->device) {
                $device_subscribed = true;
            }
        }

        $this->js_config['device_subscribed'] = $device_subscribed;
    }

    public function renderHTML() {
        $args = [
            'push_public__key'  => config(php_uname('n') . '.PUSH_PUBLIC'),
            'is_logged'         => UserService::isUserLoggedIn(),
        ];
        return view('NotificationsAllow/templates/NotificationsAllow', $args);
    }



    public function notificationsAllowed($params) {
        $endpoint = $params['endpoint'];
        $p256dh   = $params['p256dh'];
        $auth     = $params['auth'];

        return NotificationService::createSubscription($endpoint, $p256dh, $auth);
    }
}
