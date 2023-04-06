<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Components\Tabs;
use App\Components\UserNotificationsSettings;
use App\Components\UserProfileInfo;
use App\Providers\AddressService;
use App\Providers\SessionService;
use App\Providers\ShopService;
use App\Providers\UserService;

/**
 * Manipulacija korisnickim informacijama (ime, lozinka, aktivacioni mejl...)
 */
class UserProfile extends BaseComponent {
    protected $composite = true;

    private $cart        = null;
    private $wishlist    = null;
    private $order       = null;
    private $user_id     = null;
    private $info        = [];
    private $tabs        = null;
    protected $js = [
        'UserProfile/js/UserProfile.js',
    ];

    protected $css = [
        'UserProfile/css/UserProfile.css',
    ];

    protected $icons    = ['UserProfile/templates/icons'];

    public function __construct(
        $cart = null,
        $wishlist = null,
        $order = null,
        $info = [],
        $active_tab = ''
    ) {
        $components = [];

        if ($cart !== null) {
            array_push($components, $cart);
        }

        if ($wishlist !== null) {
            array_push($components, $wishlist);
        }

        if ($order !== null) {
            array_push($components, $order);
        }

        $profile_settings   = new UserProfileInfo($info);
        $profile_addresses  = new UserProfileAddresses($info);
        $notifications      = new UserNotificationsSettings();
        $tabs = [
            [
                'label' => 'Profil',
                'component' => $profile_settings,
                'has_notifications' => false,
                'active' => true,
            ],

            [
                'label' => 'Adrese',
                'component' => $profile_addresses,
                'has_notifications' => false,
            ],
            [
                'label' => 'Notifikacije',
                'component' => $notifications,
                'has_notifications' => false,
            ],
        ];

        if ($cart !== null) {
            array_push($tabs, [
                'label' => 'Korpa',
                'component' => $cart,
                'has_notifications' => false,
            ]);
        }

        if ($wishlist !== null) {
            array_push($tabs, [
                'label' => 'Lista želja',
                'component' => $wishlist,
                'has_notifications' => false,
            ]);
        }

        if ($order !== null) {
            array_push($tabs, [
                'label' => 'Narudžbine',
                'component' => $order,
                'has_notifications' => ShopService::hasUnconfirmedOrders(),
            ]);
        }
        $this->tabs = new Tabs($tabs, $active_tab);

        if ($notifications !== null) {
            array_push($components, $notifications);
        }
        if ($profile_settings !== null) {
            array_push($components, $profile_settings);
        }

        if ($profile_addresses !== null) {
            array_push($components, $profile_addresses);
        }

        if ($this->tabs !== null) {
            array_push($components, $this->tabs);
        }

        parent::__construct($components);

        $this->cart     = $cart;
        $this->wishlist = $wishlist;
        $this->order    = $order;
        $this->user_id  = UserService::getCurrentUserId();
        $this->info     = $info;
    }

    public function renderHTML() {
        $args = [
            'addresses'     => AddressService::getAddressesByUserId($this->user_id),
            'csrf_field'    => SessionService::getCsrfField(),
            'cart'          => $this->cart,
            'user'          => UserService::getCurrentUser(),
            'wishlist'      => $this->wishlist,
            'order'         => $this->order,
            'info'          => $this->info,
            'tabs'          => $this->tabs,
        ];

        return
            view('UserProfile/templates/UserProfile', $args)
        ;
    }





    /*create*/





    public function createAddress($params) {
        $name           = $params['name'];
        $surname        = $params['surname'];
        $address        = $params['address'];
        $phone          = $params['phone'];
        $company        = $params['company'];
        $user           = $this->user_id;
        $city           = $params['city'];
        $postal_code    = $params['postal_code'];
        $pib            = $params['pib'];

        return AddressService::createAddressUser(
            $user,
            $city,
            $name,
            $surname,
            $address,
            $postal_code,
            $phone,
            false,
            false,
            $company,
            $pib
        );
    }





    /* read */





    public function getAddresses($params) {
        return AddressService::getAddressesByUserId($this->user_id);
    }

    public function getOrders($params) {
        return ShopService::getOrdersByUserId($this->user_id);
    }



    /* update */





    public function editAddress($params) {
        $address_id     = $params['address_id'];
        $name           = $params['name'];
        $surname        = $params['surname'];
        $address        = $params['address'];
        $phone          = $params['phone'];
        $company        = $params['company'];
        $city           = $params['city'];
        $postal_code    = $params['postal_code'];
        $pib            = $params['pib'];


        return AddressService::updateAddress(
            $address_id,
            [
                'contact_name'      => $name,
                'contact_surname'   => $surname,
                'address'           => $address,
                'phone_nr'          => $phone,
                'company'           => $company,
                'city'              => $city,
                'postal_code'       => $postal_code,
                'pib'               => $pib            ]
        );
    }

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
        $name       = $params['name'];
        $surname    = $params['surname'];
        $phone_nr   = $params['phone_nr'];

        return UserService::updateLocalUser(
            $user_id,
            [
                'username'          => $username,
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

    public function checkAddress($params) {
        $user_id = UserService::getCurrentUserId();
        $address_id = intval($params['address_id']);
        $address = strval($params['address']);
        return AddressService::isAddressDuplicate($user_id, $address_id, $address);
    }


    public function checkPhone($params) {
        $user_id = UserService::getCurrentUserId();
        $address_id = intval($params['address_id']);
        $phone = strval($params['phone']);
        return AddressService::isPhoneDuplicate($user_id, $address_id, $phone);
    }

    /*Slanje emaila */


    public function sendEmail($params) {
        $user_id = UserService::getCurrentUserId();
        $user = UserService::getUserById($user_id);
        return UserService::sendValidateEmail($user->email);
    }



    /*delete */

    public function deleteAddress($params) {
        $address_id = intval($params['id']);

        return AddressService::deleteAddress($address_id);
    }

    public function cancelOrder($params) {
        $order_id = intval($params['id']);
        return ShopService::deleteOrder($order_id);
    }
}
