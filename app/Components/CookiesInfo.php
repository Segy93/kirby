<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\SessionService;
use App\Providers\UserService;

/**
 * Saglasnost sa politikom koriscenja kolacica
 */
class CookiesInfo extends BaseComponent {
    protected $composite = true;
    protected $css       = ['CookiesInfo/css/CookiesInfo.css'];
    protected $js        = ['CookiesInfo/js/CookiesInfo.js'];


    public function renderHTML() {
        $cookies_accepted = false;
        if (UserService::isUserLoggedIn()) {
            $user = UserService::getCurrentUser();
            $cookies_accepted = $user->cookies_accepted;
        } else {
            $cookies_accepted = SessionService::getSessionValueForService('cookies_accepted', 'user_service');
            if ($cookies_accepted === null) {
                $cookies_accepted = false;
            }
        }

        $args = [
            'cookies_accepted'  => $cookies_accepted,
            'csrf_field'        => SessionService::getCsrfField(),
        ];
        return view('CookiesInfo/templates/CookiesInfo', $args);
    }

    public function cookieAccepted() {
        if (UserService::isUserLoggedIn()) {
            $user_id = UserService::getCurrentUserId();
            UserService::updateLocalUser($user_id, ['cookies_accepted' => true]);
        } else {
            SessionService::setSessionForService('cookies_accepted', true, false, 'user_service');
        }

        return true;
    }
}
