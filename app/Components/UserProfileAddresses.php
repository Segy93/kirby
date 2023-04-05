<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\AddressService;
use App\Providers\SessionService;
use App\Providers\UserService;

/**
 * Komponenta za adrese korisnika u novom tabu
 */
class UserProfileAddresses extends BaseComponent {
    private $user_id        = null;
    protected $css       = [
        'UserProfileAddresses/css/UserProfileAddresses.css',
    ];
    protected $js        = [
        'UserProfileAddresses/js/UserProfileAddresses.js',
    ];

    public function __construct() {
        $this->user_id  = UserService::getCurrentUserId();
    }


    public function renderHTML() {
        $logged_in = UserService::isUserLoggedIn();
        $args = [
            'addresses'     => AddressService::getAddressesByUserId($this->user_id),
            'csrf_field'    => SessionService::getCsrfField(),
            'isLoggedIn'    => $logged_in,
            'user'          => $logged_in ? UserService::getCurrentUser() : null,
        ];
        return
            view('UserProfileAddresses/templates/UserProfileAddresses', $args)
            // . view('UserProfileInfo/templates/UserProfileInfo', $args)
        ;
    }



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
                'pib'               => $pib
            ]
        );
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


    /* read */





    public function getAddresses($params) {
        return AddressService::getAddressesByUserId($this->user_id);
    }


    /*delete */

    public function deleteAddress($params) {
        $address_id = intval($params['id']);

        return AddressService::deleteAddress($address_id);
    }
}
