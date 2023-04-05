<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Components\NotificationsAllow;
use App\Providers\DeviceService;
use App\Providers\NotificationService;

/**
 *
 */
class UserNotificationsSettings extends BaseComponent {
    protected $composite = true;
    protected $css       = ['UserNotificationsSettings/css/UserNotificationsSettings.css'];
    protected $js        = ['UserNotificationsSettings/js/UserNotificationsSettings.js'];
    private $allow_component = null;


    public function __construct() {
        $this->allow_component  = new NotificationsAllow();
        parent::__construct([$this->allow_component]);
    }

    public function renderHTML() {
        $subscriptions    = NotificationService::getAllUserSubscriptions();
        $subscriptions    = array_map('self::getTypeId', $subscriptions);
        $args = [
            'notification_types' => NotificationService::getAllNotificationTypes(),
            'user_endpoints'     => NotificationService::getUserEndpoints(),
            'user_subscriptions' => $subscriptions,
            'current_device'     => DeviceService::getDeviceInfo(),
            'allow_component'    => $this->allow_component,
        ];
        return view('UserNotificationsSettings/templates/UserNotificationsSettings', $args);
    }

    public function getTypeId($sub) {
        return $sub->type_id;
    }

    public function getUserEndpoints() {
        return NotificationService::getUserEndpoints();
    }

    public function changeNotificationSubscription($params) {
        $type_id = intval($params['type_id']);

        return NotificationService::toggleUserSubscription($type_id);
    }

    public function removeEndpoint($params) {
        $id = intval($params['endpoint_id']);

        return NotificationService::removeEndpoint($id);
    }
}
