<?php

namespace App\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Attributes")
 */
class Attribute implements \JsonSerializable {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $category_id;

    /**
     * @ORM\Column(type="string")
     */
    private $machine_name;

    /**
     * @ORM\Column(type="string")
     */
    private $name_import;

    /**
     * @ORM\Column(type="string")
     */
    private $label;

    /**
     * @ORM\Column(type="string")
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     */
    private $order_category;

    /**
     * @ORM\Column(type="integer")
     */
    private $order_filter;

    /**
     * @ORM\Column(type="integer")
     */
    private $order_product;

    /**
     * @ORM\Column(type="integer")
     */
    private $order_url;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="attributes")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="AttributeValue", mappedBy="attribute")
     */
    private $attribute_values;

    public function __construct() {
        $this->attribute_values = new ArrayCollection();
    }

    public function __get($fieldName) {
        return $this->{$fieldName};
    }

    public function __set($fieldName, $value) {
        $this->{$fieldName} = $value;
    }

    public function jsonSerialize() {
        return [
            'id'        =>  $this->id,
            'label'     =>  $this->label,
        ];
    }

    public function addValue(AttributeValue $attribute_value) {
        $attribute_value->setAttribute($this);
        $this->attribute_values->add($attribute_value);
    }
}
