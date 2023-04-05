<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Notifications__Types")
 */
class NotificationsType implements \JsonSerializable {
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
    private $machine_name;

    /**
     * @ORM\Column(type="integer")
     */
    private $position;


    /**
     * json Serilizacija
     * @return void
     */
    public function jsonSerialize() {
        return [
            'id'            =>  $this->id,
            'name'          =>  $this->name,
            'machine_name'  =>  $this->machine_name,
            'position'      =>  $this->position,
        ];
    }


    public function __get($fieldName) {
        return $this->{$fieldName};
    }

    public function __set($fieldName, $value) {
        $this->{$fieldName} = $value;
    }
}
