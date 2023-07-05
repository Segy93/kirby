<?php

namespace App\Models;

use App\Providers\ShopService;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="OrderUpdates")
 */
class OrderUpdate implements \JsonSerializable {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $order_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $admin_id;

    /**
     * @ORM\Column(type="string")
     */
    private $comment_admin;

    /**
     * @ORM\Column(type="string")
     */
    private $comment_user;

    /**
     * @ORM\Column(type="boolean")
     */
    private $user_notified;

    /**
     * @ORM\Column(type="integer")
     */
    private $status_code;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="updates")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     */
    private $order;

    /**
     * @ORM\ManyToOne(targetEntity="Admin")
     * @ORM\JoinColumn(name="admin_id", referencedColumnName="id")
     */
    private $admin;

    private function getOrderStatus($status) {
        $statuses = ShopService::getAllOrderStatuses();

        return array_key_exists($status, $statuses) ? $statuses[$status] : null;
    }

    private function setOrderStatus($status) {
        $statuses = [
            'nepotvrđeno'           =>  0,
            'potvrđeno'             =>  1,
            'poslata profaktura'    =>  2,
            'obrađena uplata'       =>  3,
            'spremno za slanje'     =>  4,
            'poslato'               =>  5,
            'stiglo'                =>  6,
            'otkazano'              =>  7,
            'u obradi'              =>  8,
            'spremno za preuzimanje'=>  9,
            'preuzeto'              =>  10,
            'stornirano'            =>  11,
        ];

        $this->status_code = array_key_exists($status, $statuses) ? $statuses[$status] : 0;
    }

    /**
     * json Serilizacija
     * @return mixed
     */
    public function jsonSerialize(): mixed {
        return [
            'id'            =>  $this->id,
            'order_id'      =>  $this->order_id,
            'admin_id'      =>  $this->admin_id,
            'comment_admin' =>  $this->comment_admin,
            'comment_user'  =>  $this->comment_user,
            'user_notified' =>  $this->user_notified,
            'status'        =>  $this->status,
            'date'          =>  $this->date,
            'date_formatted'=>  $this->date_formatted,
            // 'order'         =>  $this->order,
            'user'          =>  !empty($this->order->user) ? $this->order->user->username : null,
            'admin'         =>  !empty($this->admin) ? $this->admin->username : null,
        ];
    }

    public function __construct() {
        $this->user_notified    =   false;
        $this->status_code      =   0;
        $this->date             =   new \DateTime();
    }

    public function __get($fieldName) {
        if ($fieldName === 'status') {
            return $this->getOrderStatus($this->status_code);
        }

        if ($fieldName === 'date_formatted') {
            return $this->date->setTimezone(new \DateTimeZone('Europe/Belgrade'))->format('d.m.Y. H:i:s');
        }

        return $this->{$fieldName};
    }

    public function __set($fieldName, $value) {
        if ($fieldName === 'status') {
            $this->setOrderStatus($value);
        }

        $this->{$fieldName} = $value;
    }
}
