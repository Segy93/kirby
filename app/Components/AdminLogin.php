<?php

namespace App\Components;

use App\Providers\SessionService;
/**
*
*/
class AdminLogin extends BaseComponent {
    public function renderHTML() {
        $unsuccesful_login_attempts = 0;
        if (array_key_exists('admin_controller', $_SESSION)
            && array_key_exists('failed_attempts', $_SESSION['admin_controller'])
        ) {
            $unsuccesful_login_attempts = $_SESSION['admin_controller']['failed_attempts'];
        }

        return view('AdminLogin/templates/AdminLogin', [
            'unsuccesful_login_attempts'    => $unsuccesful_login_attempts,
            'csrf_field'                    => SessionService::getCsrfField(),
        ]);
    }
}
