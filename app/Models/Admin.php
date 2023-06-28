<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Admins")
 */
class Admin implements \JsonSerializable {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="integer")
     */
    public $role_id;

     /**
     * @ORM\Column(type="string")
     */
    public $username;

    /**
     * @ORM\Column(type="string")
     */
    public $email;

    /**
     * @ORM\Column(type="string")
     */
    public $password;

    /**
     * @ORM\ManyToOne(targetEntity="Role", inversedBy="admins")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     */
    public $role;

    /**
     * @ORM\OneToMany(targetEntity="App\Models\Articles\Article", mappedBy="author")
     */
    public $articles;

    /**
     * json Serilizacija
     * @return mixed
     */
    public function jsonSerialize(): mixed {
        return [
            'id'                =>  $this->id,
            'username'          =>  $this->username,
            'role_id'           =>  $this->role_id,
            'email'             =>  $this->email,
        ];
    }

    public function __get($fieldName) {
        return $this->{$fieldName};
    }

    public function __set($fieldName, $value) {
        $this->{$fieldName} = $value;
    }
}
