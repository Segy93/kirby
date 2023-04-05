<?php

namespace App\Models\Addresses;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Addresses__Shop")
 */
class AddressShop extends Address {

    /**
     * @ORM\Column(type="integer")
     */
    public $shop_id;

    /**
     * @ORM\Column(type="string")
     */
    public $email;

    /**
     * @ORM\Column(type="string")
     */
    public $fax;

    /**
     * @ORM\Column(type="string")
     */
    public $open_hours;

    /**
     * @ORM\OneToOne(targetEntity="App\Models\Shop", inversedBy="address")
     * @ORM\JoinColumn(name="shop_id", referencedColumnName="id")
     */
    public $shop;

    protected $address_type = 'shop';
    /**
     * json Serilizacija
     * @return void
     */
    public function jsonSerialize() {
        $json = [
            'shop_id'       =>  $this->shop_id,
            'email'         =>  $this->email,
            'fax'           =>  $this->fax,
            'open_hours'    =>  $this->open_hours,
            'address_type'  =>  $this->address_type,
        ];

        return array_merge(parent::jsonSerialize(), $json);
    }


    private function getOpenHours() {
        return str_replace('\n', "<br/>", $this->open_hours);
    }

    public function __get($fieldName) {
        if ($fieldName === 'open_hours_field') {
            return $this->getOpenHours();
        }

        if ($fieldName === 'fax_link') {
            $prefixed = preg_replace('/^0/', '+381', $this->fax);
            return str_replace(['-', '/'], '', $prefixed);
        }

        return $this->{$fieldName};
    }

    public function __set($fieldName, $value) {
        $this->{$fieldName} = $value;
    }
}
