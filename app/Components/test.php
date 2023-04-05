<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\NotificationService;

/**
 *
 */
class Test extends BaseComponent {

    public function sendNotification($params) {
        $applicationServerPublicKey = $params['applicationServerPublicKey'];
        return NotificationService::sendNotification($applicationServerPublicKey);
    }
}
