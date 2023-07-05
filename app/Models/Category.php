<?php

namespace App\Models;

use App\Providers\SEOService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Categories")
 */
class Category implements \JsonSerializable {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     */
    private $name_import;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity="CategoryField", mappedBy="category")
     */
    private $fields;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="category")
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity="Attribute", mappedBy="category")
     */
    private $attributes;

    public function __construct() {
        $this->fields = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->attributes = new ArrayCollection();
    }

    public function jsonSerialize(): mixed {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'name_import' => $this->name_import,
        ];
    }

    public function __get($fieldName) {
        if ($fieldName === 'url') {
            return SEOService::getSEObyMachineName('category_' . $this->id)->url;
        }

        return $this->{$fieldName};
    }

    public function __set($fieldName, $value) {
        $this->{$fieldName} = $value;
    }
}
