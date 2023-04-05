<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\SessionService;
use App\Providers\ShopService;
use App\Providers\UserService;

/**
 *
 */
class UserProfileInfo extends BaseComponent {
    private $user_id                = null;
    private $info                   = [];
    protected $css       = [
        'UserProfileInfo/css/UserProfileInfo.css',
    ];
    protected $js        = [
        'UserProfileInfo/js/UserProfileInfo.js',
    ];

    public function __construct($info = []) {
        $this->info     = $info;
        $this->user_id  = UserService::getCurrentUserId();
    }


    public function renderHTML() {
        $logged_in = UserService::isUserLoggedIn();
        $args = [
            'csrf_field'            => SessionService::getCsrfField(),
            'isLoggedIn'            => $logged_in,
            'user'                  => $logged_in ? UserService::getCurrentUser() : null,
            'info'                  => $this->info,
        ];
        return
            view('UserProfileInfo/templates/UserProfileInfo', $args)
        ;
    }




    public function getOrders($params) {
        return ShopService::getOrdersByUserId($this->user_id);
    }



    /* update */




    public function userImageChange($params) {
        $user_id    = UserService::getCurrentUserId();
        $image      = $params['image'];

        return UserService::updateLocalUser($user_id, [
            'profile_picture' => $image,
        ]);
    }

    public function userChange($params) {
        $user_id    = UserService::getCurrentUserId();
        $username   = $params['username'];
        $email      = $params['email'];
        $name       = $params['name'];
        $surname    = $params['surname'];
        $phone_nr   = $params['phone_nr'];

        return UserService::updateLocalUser(
            $user_id,
            [
                'username'          => $username,
                'email'             => $email,
                'name'              => $name,
                'surname'           => $surname,
                'phone_nr'          => $phone_nr,
            ]
        );
    }


    /*Promena lozinke */

    public function changePassword($params) {
        $user_id            = UserService::getCurrentUserId();
        $password_old       = $params['password_old'];
        $password           = $params['password'];
        $password_confirm   = $params['password_confirm'];

        return UserService::updateLocalUser(
            $user_id,
            [
                'password_old'      => $password_old,
                'password'          => $password,
                'password_confirm'  => $password_confirm,
            ]
        );
    }

    public function checkPassword($params) {
        $user_id = UserService::getCurrentUserId();
        $password_old = strval($params['password_old']);
        return UserService::checkCurrentPassword($user_id, $password_old);
    }

    /*Slanje emaila */


    public function sendEmail($params) {
        $user_id = UserService::getCurrentUserId();
        $user = UserService::getUserByID($user_id);
        return UserService::sendValidateEmail($user->email);
    }



    /*delete */

    public function cancelOrder($params) {
        $order_id = intval($params['id']);
        return ShopService::deleteOrder($order_id);
    }
}
