<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Shops")
 */
class Shop implements \JsonSerializable {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string")
     */
    public $name;

    /**
     * @ORM\OneToOne(targetEntity="App\Models\Addresses\AddressShop", mappedBy="shop")
     */
    public $address;

    /**
     * @ORM\OneToMany(targetEntity="App\Models\PhoneNumber", mappedBy="shop")
     */
    public $phones;

    /**
     * json Serilizacija
     * @return mixed
     */
    public function jsonSerialize(): mixed {
        return [
            'id'        =>  $this->id,
            'name'      =>  $this->name,
            'address'   =>  $this->address,
            'phones'    =>  $this->phones,
        ];
    }

    public function __get($fieldName) {
        return $this->{$fieldName};
    }

    public function __set($fieldName, $value) {
        $this->{$fieldName} = $value;
    }
}
