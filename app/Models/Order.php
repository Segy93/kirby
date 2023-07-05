<?php

namespace App\Models;

use App\Providers\ShopService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Orders")
 * @ORM\HasLifecycleCallbacks
 */
class Order implements \JsonSerializable {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $user_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $payment_method_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $delivery_address_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $billing_address_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_order;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_delivery;

    /**
     * @ORM\Column(type="string")
     */
    private $online_token;

    /**
     * @ORM\Column(type="string")
     */
    private $note;

    /**
     * @ORM\Column(type="integer")
     */
    private $total_price;

    /**
     * @ORM\Column(type="integer")
     */
    private $shipping_fee;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="orders")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="PaymentMethod")
     * @ORM\JoinColumn(name="payment_method_id", referencedColumnName="id")
     */
    private $payment_method;

    /**
     * @ORM\ManyToOne(targetEntity="App\Models\Addresses\Address")
     * @ORM\JoinColumn(name="delivery_address_id", referencedColumnName="id")
     */
    private $delivery_address;

    /**
     * @ORM\ManyToOne(targetEntity="App\Models\Addresses\Address")
     * @ORM\JoinColumn(name="billing_address_id", referencedColumnName="id")
     */
    private $billing_address;

    /**
     * @ORM\OneToMany(targetEntity="OrderProduct", mappedBy="order", cascade={"persist"}, orphanRemoval=true)
     */
    private $order_products;

    /**
     * @ORM\OneToMany(targetEntity="OrderUpdate", mappedBy="order", cascade={"persist"}, orphanRemoval=true)
     */
    private $updates;

    private function getLastUpdate() {
        return $this->updates->last();
    }

    private function getOrderWeight() {
        return ShopService::calculateShippingWeight($this->id);
    }

    /**
     * json Serilizacija
     * @return mixed
     */
    public function jsonSerialize(): mixed {
        return [
            'id'                        =>  $this->id,
            'user_id'                   =>  $this->user_id,
            'payment_method_id'         =>  $this->payment_method_id,
            'delivery_address_id'       =>  $this->delivery_address_id,
            'billing_address_id'        =>  $this->billing_address_id,
            'date_order'                =>  $this->date_order,
            'date_order_formatted'      =>  $this->date_order_formatted,
            'date_delivery'             =>  $this->date_delivery,
            'date_delivery_formatted'   =>  $this->date_delivery_formatted,
            'note'                      =>  $this->note,
            'total_price'               =>  $this->total_price,
            'total_price_formatted'     =>  $this->total_price_formatted,
            'shipping_fee'              =>  $this->shipping_fee,
            'shipping_fee_formatted'    =>  $this->shipping_fee_formatted,
            'user'                      =>  $this->user,
            'payment_method'            =>  $this->payment_method,
            'delivery_address'          =>  $this->delivery_address,
            'billing_address'           =>  $this->billing_address,
            'order_products'            =>  $this->order_products->getValues(),
            'updates'                   =>  $this->updates->getValues(),
            'last_update'               =>  $this->last_update,
            'weight'                    =>  $this->weight,
        ];
    }

    public function __construct() {
        $this->date_order       =   new \DateTime();
        $this->total_price      =   0;
        $this->shipping_fee     =   0;
        $this->order_products   =   new ArrayCollection();
        $this->updates          =   new ArrayCollection();
    }

    public function __get($fieldName) {
        if ($fieldName === 'last_update') {
            return $this->getLastUpdate();
        }

        if ($fieldName === 'weight') {
            return $this->getOrderWeight();
        }

        if ($fieldName === 'total_price_formatted') {
            return number_format($this->total_price, 2, ',', '.');
        }

        if ($fieldName === 'shipping_fee_formatted') {
            return number_format($this->shipping_fee, 2, ',', '.');
        }

        if ($fieldName === 'date_order_formatted') {
            return $this->date_order->setTimezone(new \DateTimeZone('Europe/Belgrade'))->format('d.m.Y.');
        }

        if ($fieldName === 'date_delivery_formatted') {
            if ($this->date_delivery !== null) {
                $date_delivery_formatted = $this->date_delivery->setTimezone(
                    new \DateTimeZone('Europe/Belgrade')
                )->format('d.m.Y.');
            } else {
                $date_delivery_formatted = null;
            }
            return $date_delivery_formatted;
        }

        return $this->{$fieldName};
    }

    public function __set($fieldName, $value) {
        $this->{$fieldName} = $value;
    }
}
