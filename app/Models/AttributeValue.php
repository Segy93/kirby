<?php

namespace App\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="AttributeValues")
 */
class AttributeValue implements \JsonSerializable {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $attribute_id;

    /**
     * @ORM\Column(type="string")
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="Attribute")
     * @ORM\JoinColumn(name="attribute_id", referencedColumnName="id")
     */
    private $attribute;

    /**
     * @ORM\OneToMany(targetEntity="ProductAttribute", mappedBy="attribute_value")
     */
    private $product_attributes;


    /**
     * json Serilizacija
     * @return void
     */
    public function jsonSerialize() {
        return [
            'id'    => $this->id,
            'label' => $this->attribute->label,
            'value' => $this->value,
        ];
    }

    public function setAttribute(Attribute $attribute) {
        $this->attribute = $attribute;
    }

    public function __construct() {
        $this->product_attributes = new ArrayCollection();
    }

    public function __get($fieldName) {
        return $this->{$fieldName};
    }

    public function __set($fieldName, $value) {
        $this->{$fieldName} = $value;
    }
}
