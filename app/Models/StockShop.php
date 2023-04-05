<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="StockShops")
 */
class StockShop {
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
     * @ORM\Column(type="integer")
     */
    public $product_id;

    /**
     * @ORM\ManyToOne(targetEntity="Product", cascade={"persist"})
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    public $product;

    public function setProduct(Product $product) {
        $this->product = $product;
    }

    public function __get($fieldName) {
        return $this->{$fieldName};
    }

    public function __set($fieldName, $value) {
        $this->{$fieldName} = $value;
    }
}
