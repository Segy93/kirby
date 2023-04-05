<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ProductAttributes")
 */
class ProductAttribute {
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
     * @ORM\Column(type="integer")
     */
    public $attribute_value_id;

    /**
     * @ORM\ManyToOne(targetEntity="Product", cascade={"persist"})
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    public $product;

    /**
     * @ORM\ManyToOne(targetEntity="AttributeValue", inversedBy="product_attributes", cascade={"persist"})
     * @ORM\JoinColumn(name="attribute_value_id", referencedColumnName="id")
     */
    public $attribute_value;

    public function setProduct(Product $product) {
        $this->product = $product;
    }

    public function setAttributeValue(AttributeValue $attribute_value) {
        $this->attribute_value = $attribute_value;
    }

    public function __get($fieldName) {
        return $this->{$fieldName};
    }

    public function __set($fieldName, $value) {
        $this->{$fieldName} = $value;
    }
}
