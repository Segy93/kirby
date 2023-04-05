<?php

namespace App\Models\Configuration;

use Doctrine\ORM\Mapping as ORM;

/**
 * Model za vezu konfiguracije i proizvoda
 *
 * @ORM\Entity
 * @ORM\Table(name="Configuration__Products")
 * @ORM\HasLifecycleCallbacks
 */
class ConfigurationProduct implements \JsonSerializable {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $configuration_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $product_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity="ConfigurationMain")
     * @ORM\JoinColumn(name="configuration_id", referencedColumnName="id")
     */
    private $configuration;

    /**
     * @ORM\ManyToOne(targetEntity="App\Models\Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;

    /**
     * json Serilizacija
     * @return void
     */
    public function jsonSerialize() {
        return [
            'id'                =>  $this->id,
            'configuration_id'  =>  $this->configuration_id,
            'configuration'     =>  $this->configuration,
            'product_id'        =>  $this->product_id,
            'quantity'          =>  $this->quantity,
            'product'           =>  $this->product,
        ];
    }

    public function __get($fieldName) {
        return $this->{$fieldName};
    }

    public function __set($fieldName, $value) {
        $this->{$fieldName} = $value;
    }
}
