<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\AddressService;
use App\Providers\PermissionService;
use App\Providers\SessionService;
use App\Providers\UserService;




class AdminUserAddresses extends BaseComponent {


    protected $js = [];
    protected $css = [
        'AdminUserAddresses/css/AdminUserAddresses.css',
    ];

    private $id = null;

    public function renderHTML() {
        $args = [
            'permissions' => [
                'address_create' => PermissionService::checkPermission('address_create'),
                'address_read'   => PermissionService::checkPermission('address_read'),
                'address_update' => PermissionService::checkPermission('address_update'),
                'address_delete' => PermissionService::checkPermission('address_delete'),
            ],
            'addresses'          => AddressService::getAddressesByUserId($this->id),
            'id'                 => $this->id,
            'csrf_field'         => SessionService::getCsrfField(),
            'user'               => UserService::getUserById($this->id),
        ];
        return
            view('AdminUserAddresses/templates/AdminUserAddressesCreate', $args)
            . view('AdminUserAddresses/templates/AdminUserAddressesList', $args)
            . view('AdminUserAddresses/templates/AdminUserAddressesChange', $args)
            . view('AdminUserAddresses/templates/AdminUserAddressesDelete', $args)
        ;
    }
    public function __construct($user_id = null) {
        if (PermissionService::checkPermission('address_update')) {
            $this->js[] = 'AdminUserAddresses/js/AdminUserAddressesChange.js';
        }

        if (PermissionService::checkPermission('address_delete')) {
            $this->js[] = 'AdminUserAddresses/js/AdminUserAddressesDelete.js';
        }

        if (PermissionService::checkPermission('address_create')) {
            $this->js[] = 'AdminUserAddresses/js/AdminUserAddressesCreate.js';
        }

        if (PermissionService::checkPermission('address_read')) {
            $this->js[] = 'AdminUserAddresses/js/AdminUserAddressesList.js';
        }

        $this->id = $user_id;
    }



    public function createAddress($params) {
        $user_id            = $params['user_id'];
        $contact_name       = $params['contact_name'];
        $contact_surname    = $params['contact_surname'];
        $street_address     = $params['address'];
        $postal_code        = $params['post_code'];
        $phone_nr           = $params['phone'];
        $company            = $params['company'];
        $city               = $params['city'];
        $pib                = $params['pib'];

        return AddressService::createAddressUser(
            $user_id,
            $city,
            $contact_name,
            $contact_surname,
            $street_address,
            $postal_code,
            $phone_nr,
            false,
            false,
            $company,
            $pib
        );
    }


    public function fetchData($params) {
        $search         = $params['search'];
        $user_id        = intval($params['user_id']);
        // $direction      = boolval($params['direction']);
        // $limit          = intval($params['limit']);

        $user_id = $user_id === 0 ? null : $user_id;

        return AddressService::getAddressesByUserId($user_id, $search);
    }

    public function getAddressById($params) {
        $address_id     = intval($params['address_id']);

        return AddressService::getAddressById($address_id);
    }

    /**
     * Promena informacija adrese
     * @param   string
     * @param   string
     * @param   string
     * @param   string
     * @param   string
     * @param   integer
     * @param   string
     **/
    public function updateAddress($params) {
        $address_id        = intval($params['address_id']);

        $updates = [
        ];

        if (!empty($params['contact_name'])) {
            $updates ['contact_name']                   = $params['contact_name'];
        }

        if (!empty($params['contact_surname'])) {
            $updates ['contact_surname']                = $params['contact_surname'];
        }

        if (!empty($params['company'])) {
            $updates ['company']                        = $params['company'];
        }

        if (!empty($params['phone_nr'])) {
            $updates ['phone_nr']                       = $params['phone_nr'];
        }

        if (!empty($params['address'])) {
            $updates ['address']                        = $params['address'];
        }

        if (!empty($params['postal_code'])) {
            $updates ['postal_code']                    = $params['postal_code'];
        }

        if (!empty($params['city'])) {
            $updates ['city']                           = $params['city'];
        }

        if (!empty($params['pib'])) {
            $updates ['pib']                             = $params['pib'];
        }


        return AddressService::updateAddress($address_id, $updates);
    }


    public function deleteAddress($params) {
        $address_id = $params['address_id'];

        return AddressService::deleteAddress($address_id);
    }
}
