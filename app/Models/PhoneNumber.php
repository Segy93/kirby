<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="PhoneNumbers")
 */
class PhoneNumber implements \JsonSerializable {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="integer")
     */
    public $shop_id;

    /**
     * @ORM\Column(type="string")
     */
    public $phone_nr;

    /**
     * @ORM\ManyToOne(targetEntity="App\Models\Shop", inversedBy="phones")
     * @ORM\JoinColumn(name="shop_id", referencedColumnName="id")
     */
    public $shop;

    /**
     * json Serilizacija
     */
    public function jsonSerialize(): mixed {
        $json = [
            'phone_nr'       =>  $this->phone_nr,
            'phone_nr_link'  =>  $this->phone_nr_link,
        ];

        return $json;
    }

    public function __get($fieldName) {
        if ($fieldName === 'phone_nr_link') {
            $prefixed = preg_replace('/^0/', '+381', $this->phone_nr);
            return str_replace(['-', '/'], '', $prefixed);
        }

        return $this->{$fieldName};
    }

    public function __set($fieldName, $value) {
        $this->{$fieldName} = $value;
    }
}
