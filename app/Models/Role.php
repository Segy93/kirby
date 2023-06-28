<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Roles")
 */
class Role implements \JsonSerializable {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="Admin", mappedBy="role")
     */
    private $admins;

    /**
     * @ORM\ManyToMany(targetEntity="Permission")
     * @ORM\JoinTable(name="RolePermissions")
     */
    private $permissions;

    public function jsonSerialize(): mixed {
        $permissions = $this->permissions === null ? [] : $this->permissions->getValues();
        return [
            'id'                =>  $this->id,
            'description'       =>  $this->description,
            'admins'            =>  $this->admins,
            'permissions'       =>  $permissions,
        ];
    }

    public function __get($fieldName) {
        return $this->{$fieldName};
    }

    public function __set($fieldName, $value) {
        $this->{$fieldName} = $value;
    }
}
