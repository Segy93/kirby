<?php

namespace App\Components;

use App\Providers\AddressService;
use App\Providers\AdminService;
use App\Providers\PermissionService;
use App\Providers\SearchService;
use App\Providers\SessionService;
use App\Providers\ShopService;

class AdminOrder extends BaseComponent {

    private $order_id = null;

    protected $js = [
        'AdminOrder/js/AdminOrderAddresses.js',
        'AdminOrder/js/AdminOrderProducts.js',
        'AdminOrder/js/AdminOrderBillingInfo.js',
        'AdminOrder/js/AdminOrderDeliveryInfo.js',
        'AdminOrder/js/AdminOrderAddProduct.js',
        'AdminOrder/js/AdminOrderProductsDelete.js',
        'AdminOrder/js/AdminOrderStatusHistory.js',
    ];

    protected $css = [
        'AdminOrder/css/AdminOrderProducts.css',
        'AdminOrder/css/AdminOrderAddresses.css',
    ];

    public function __construct($order_id = null) {
        $this->order_id = $order_id;
    }

    public function renderHTML() {
        $order = ShopService::getOrderById($this->order_id);

        $args = [
            'order'       => $order,
            'order_items' => $order,
            'permissions' => [
                'order_create'       => PermissionService::checkPermission('order_create'),
                'order_read'         => PermissionService::checkPermission('order_read'),
                'order_update'       => PermissionService::checkPermission('order_update'),
                'order_delete'       => PermissionService::checkPermission('order_delete'),
            ],
            'statuses'          => $this->getStatuses(),
            'user_addresses'    => $this->getUserAddresses(),
            'shops'             => ShopService::getShops(),
            'csrf_field'        => SessionService::getCsrfField(),
        ];

        return
            view('AdminOrder/templates/AdminOrderAddresses', $args)
            . view('AdminOrder/templates/AdminOrderDeliveryInfo', $args)
            . view('AdminOrder/templates/AdminOrderBillingInfo', $args)
            . view('AdminOrder/templates/AdminOrderProducts', $args)
            . view('AdminOrder/templates/AdminOrderAddProduct', $args)
            . view('AdminOrder/templates/AdminOrderProductsDelete', $args)
            . view('AdminOrder/templates/AdminOrderStatusHistory', $args)
        ;
    }










    /*Create */
    public function addProduct($params) {
        $order_id   = $params['order_id'];
        $product_id = $params['product_id'];
        $quantity   = $params['quantity'];
        return ShopService::changeOrderProduct($order_id, $product_id, $quantity);
    }


    /*  Read*/

    public function fetchAddress($params) {
        $address_id = $params['address_id'];

        return AddressService::getAddressById($address_id);
    }

    public function fetchOrder($params) {
        $order_id = $params['order_id'];

        return ShopService::getOrderById($order_id);
    }

    public function fetchOrderItems($params) {
        $order_id = $params['order_id'];

        return ShopService::getOrderProductsByOrderId($order_id);
    }


    public function fetchOrderStatuses($params) {
        $order_id = $params['order_id'];

        return  ShopService::getOrderUpdates($order_id);
    }


    public function getStatuses() {
        return ShopService::getAllOrderStatuses();
    }

    public function getData($params) {
        $order_id  = intval($params['order_id']);
        $addresses = $this->getUserAddresses($params);
        $order     = ShopService::getOrderById($order_id);

        return ['addresses' => $addresses, 'order' => $order];
    }

    public function getUserAddresses($params = null) {
        if ($params === null) {
            $order_id = $this->order_id;
        } else {
            $order_id = $params['order_id'];
        }
        $order      = ShopService::getOrderById($order_id);
        $user_id    = $order->user_id;

        return AddressService::getAddressesByUserId($user_id, null, null, null, false, null, true);
    }

    public function fetchData($params) {
        $order_id = $params['order_id'];

        return ShopService::getOrderById($order_id);
    }

    public function findProducts($params) {
        $query    = $params['query'];
        $order_id = intval($params['order_id']);
        $order    = ShopService::getOrderById($order_id);
        $ids      = [];
        foreach ($order->order_products as $product) {
            array_push($ids, $product->product_id);
        }
        return SearchService::searchProductsByArtidOrName($query, $ids, true);
    }




    /* Update */
    // stara funkcija treba skloniti sva pozivanja ukljucujuci i nju
    public function updateOrder($params) {
        $order_id           = $params['order_id'];

        $delivery_address   = $params['delivery_address'];
        $note               = $params['order_note'];

        return ShopService::updateOrder($order_id, ['address_delivery' => $delivery_address, 'note' => $note]);
    }

    public function updateAddress($params) {
        $address_id = $params['address_id'];
        $address    = AddressService::getAddressById($address_id);

        $name       = $params['name'];
        $surname    = $params['surname'];
        $company    = $params['company'];
        $address_n  = $params['address'];
        $phone_nr   = $params['phone_nr'];
        $city       = $params['city'];
        $order_id   = intval($params['order_id']);
        if ($address->address_type !== 'shop') {
            $update_address = AddressService::updateAddress($address_id, [
                'contact_name'      => $name,
                'contact_surname'   => $surname,
                'address'           => $address_n,
                'phone_nr'          => $phone_nr,
                'company'           => $company,
                'city'              => $city
            ]);
            $order = ShopService::getOrderById($order_id);
            if ($order->delivery_address_id === $address_id) {
                $this->changeAddress([
                    'type' => 'delivery',
                    'address_id' => $update_address->id,
                    'order_id' => $order_id,
                ]);
            }

            if ($order->billing_address_id === $address_id) {
                $this->changeAddress([
                    'type' => 'billing',
                    'address_id' => $update_address->id,
                    'order_id' => $order_id,
                ]);
            }

            return true;
        } else {
            return false;
        }
    }

    public function changeAddress($params) {
        $type       = $params['type'];
        $address_id = intval($params['address_id']);
        $order_id   = intval($params['order_id']);
        $order      = ShopService::getOrderById($order_id);
        $send       = true;
        if ($type === 'delivery') {
            $send = $order->delivery_address_id !== $address_id;
        } else {
            $send = $order->billing_address_id !== $address_id;
        }

        $result = true;

        if ($send) {
            $result = ShopService::updateOrder($order_id, [$type . '_address_id' => $address_id]);
        }

        return  $result;
    }

    public function changeStatus($params) {
        $order_id   = $params['order_id'];
        $status     = $params['status'];
        $message    = $params['message'];
        $admin      = AdminService::getCurrentAdminId();
        $notify     = $params['notify'];
        return ShopService::updateOrder(
            $order_id,
            [
                'status' => [
                    'admin_id'      => $admin,
                    'code'          => $status,
                    'comment_admin' => $message,
                    'comment_user'  => null,
                    'user_notified' => $notify
                ],
            ]
        );
    }

    public function quantityChange($params) {
        $order_id       = $params['order_id'];
        $product_id     = $params['product_id'];
        $quantity       = $params['quantity'];

        return ShopService::changeOrderProduct($order_id, $product_id, $quantity);
    }

    /* Delete */

    public function deleteOrderProduct($params) {
        $order_product_id = $params['product_id'];

        return ShopService::deleteOrderProduct($order_product_id);
    }
}
