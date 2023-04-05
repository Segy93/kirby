<?php

namespace App\Components;

use App\Providers\PermissionService;
use App\Providers\SessionService;
use App\Providers\ShopService;

/**
*
*/
class AdminOrders extends BaseComponent {

    private $url = null;

    protected $css = [
        'AdminOrders/css/AdminOrdersList.css',
    ];

    protected $js = [
        'AdminOrders/js/AdminOrdersList.js',
    ];

    public function __construct($url = null) {
        $this->url = $url;
    }

    public function renderHTML() {
        $search_query = empty($this->url[0]) ? "" : $this->url[0]['search'];
        $args = [
            'permissions' => [
                'order_create'       => PermissionService::checkPermission('order_create'),
                'order_read'         => PermissionService::checkPermission('order_read'),
                'order_update'       => PermissionService::checkPermission('order_update'),
                'order_delete'       => PermissionService::checkPermission('order_delete'),
            ],
            'csrf_field'    => SessionService::getCsrfField(),
            'search'        => $search_query,
            'statuses'      => $this->getStatuses(),

        ];

        return
            view('AdminOrders/templates/AdminOrdersList', $args)
        ;
    }


    /* Create*/









    /*READ*/








    public function fetchData($params) {
        $search         =   $params['search'];
        $filter_status  =   $params['filter_status'] !== null ? intval($params['filter_status']) : null;
        $order_id       =   intval($params['order_id']);
        $direction      =   boolval($params['direction']);
        $limit          =   intval($params['limit']);

        return [
            'orders'        => ShopService::getOrders($order_id, $search, $filter_status, $direction, $limit),
            'statuses'      => $this->getStatuses(),
        ];
    }


    public function getStatuses() {
        return ShopService::getAllOrderStatuses();
    }

    /*UPDATE*/



    public function changeStatus($params) {
        $order_id   = $params["order_id"];
        $status     = $params["new_status"];

        return ShopService::updateOrder($order_id, ['status' => $status]);
    }

    /*DELETE*/
}
