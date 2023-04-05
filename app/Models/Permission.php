<?php

namespace App\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Permissions")
 */
class Permission implements \JsonSerializable {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string")
     */
    public $machine_name;

    /**
     * @ORM\Column(type="string")
     */
    public $description;

    /**
     * @ORM\ManyToMany(targetEntity="Role")
     * @ORM\JoinTable(name="RolePermissions")
     */
    public $roles;

    public function __construct() {
        $this->roles = new ArrayCollection();
    }

        /**
     * json Serilizacija
     * @return void
     */
    public function jsonSerialize() {
        return [
            'id'                =>  $this->id,
            'description'       =>  $this->description,
            'machine_name'      =>  $this->machine_name,
        ];
    }

    public function __get($fieldName) {
        return $this->{$fieldName};
    }

    public function __set($fieldName, $value) {
        $this->{$fieldName} = $value;
    }
}
