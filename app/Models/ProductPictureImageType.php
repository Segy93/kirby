<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ProductPictureImageType")
 */
class ProductPictureImageType implements \JsonSerializable {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="integer")
     */
    public $picture_id;

    /**
     * @ORM\Column(type="integer")
     */
    public $type_id;


    /**
     * json Serilizacija
     * @return void
     */
    public function jsonSerialize() {
        return [
            'id'                =>  $this->id,
            'product_id'        =>  $this->product_id,
            'type_id'           =>  $this->type_id,
            'image_type'        =>  $this->image_type,
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
