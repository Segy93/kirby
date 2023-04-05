<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ImageType")
 */
class ImageType implements \JsonSerializable {
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
     * @ORM\Column(type="integer")
     */
    public $width;

    /**
     * @ORM\Column(type="integer")
     */
    public $height;

    /**
     * json Serilizacija
     * @return void
     */
    public function jsonSerialize() {
        return [
            'id'          =>  $this->id,
            'name'        =>  $this->name,
            'height'      =>  $this->height,
            'width'       =>  $this->width,
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
