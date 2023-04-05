<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="CategoryFields")
 */
class CategoryField {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="integer")
     */
    public $category_id;

    /**
     * @ORM\Column(type="string")
     */
    public $name_local;

    /**
     * @ORM\Column(type="string")
     */
    public $name_import;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="fields")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    public $category;

    public function __get($fieldName) {
        return $this->{$fieldName};
    }

    public function __set($fieldName, $value) {
        $this->{$fieldName} = $value;
    }
}
