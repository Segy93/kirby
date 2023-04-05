<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="PaymentMethods")
 */
class PaymentMethod implements \JsonSerializable {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string")
     */
    public $method;


    /**
     * @ORM\Column(type="string")
     */
    public $description;

    /**
     * @ORM\Column(type="string")
     */
    public $label;

    public function __get($fieldName) {
        return $this->{$fieldName};
    }

    public function __set($fieldName, $value) {
        $this->{$fieldName} = $value;
    }

    public function jsonSerialize() {
        return [
            'id'            =>  $this->id,
            'method'        =>  $this->method,
            'description'   =>  $this->description,
            'label'         =>  $this->label,
        ];
    }
}
