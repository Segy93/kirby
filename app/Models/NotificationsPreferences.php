<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Notifications__Preferences")
 */
class NotificationsPreferences implements \JsonSerializable {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string")
     */
    public $user_id;

    /**
     * @ORM\Column(type="string")
     */
    public $endpoint;

    /**
     * @ORM\Column(type="string")
     */
    public $device;

    /**
     * @ORM\Column(type="string")
     */
    public $p256dh;

    /**
     * @ORM\Column(type="string")
     */
    public $auth;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * json Serilizacija
     * @return void
     */
    public function jsonSerialize() {
        return [
            'id'            =>  $this->id,
            'user_id'       =>  $this->user_id,
            'endpoint'      =>  $this->endpoint,
            'device'        =>  $this->device,
            'p256dh'        =>  $this->p256dh,
            'auth'          =>  $this->auth,
        ];
    }

    public function __get($fieldName) {
        return $this->{$fieldName};
    }

    public function __set($fieldName, $value) {
        $this->{$fieldName} = $value;
    }
}
