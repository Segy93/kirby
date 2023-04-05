<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ProductPictures")
 */
class ProductPicture implements \JsonSerializable {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="integer")
     */
    public $product_id;

    /**
     * @ORM\Column(type="string")
     */
    public $name;

    /**
     * @ORM\Column(type="integer")
     */
    public $position;


    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="pictures")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    public $product;

    /**
     * json Serilizacija
     * @return void
     */
    public function jsonSerialize() {
        return [
            'id'                =>  $this->id,
            'product_id'        =>  $this->product_id,
            'name'              =>  $this->name,
            'position'          =>  $this->position,
        ];
    }

    public function __construct() {
    }

    public function __get($fieldName) {
        return $this->{$fieldName};
    }

    public function __set($fieldName, $value) {
        $this->{$fieldName} = $value;
    }
}
