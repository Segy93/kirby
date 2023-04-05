<?php

namespace App\Models\Configuration;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Model za konfiguraciju
 *
 * @ORM\Entity
 * @ORM\Table(name="Configuration__Main")
 * @ORM\HasLifecycleCallbacks
 */
class ConfigurationMain implements \JsonSerializable {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $user_id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $visibility;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_created;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_updated;

    /**
     * @ORM\ManyToOne(targetEntity="App\Models\User", inversedBy="configurations")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="ConfigurationProduct", mappedBy="configuration", cascade={"persist"}, orphanRemoval=true)
     */
    private $products;

    /**
     * json Serilizacija
     * @return void
     */
    public function jsonSerialize() {
        return [
            'id'            =>  $this->id,
            'name'          =>  $this->name,
            'user_id'       =>  $this->user_id,
            'user'          =>  $this->user,
            'visibility'    =>  $this->visibility,
            'date_created'  =>  $this->date_created,
            'date_updated'  =>  $this->date_updated,
            'products'      =>  $this->products->getValues(),
        ];
    }

    public function __construct() {
        $this->products = new ArrayCollection();
    }

    public function __get($fieldName) {
        if ($fieldName === 'date_created_formatted') {
            return $this->date_created->setTimezone(new \DateTimeZone('Europe/Belgrade'))->format('d.m.Y.');
        }

        if ($fieldName === 'date_updated_formatted') {
            if ($this->date_updated !== null) {
                $date_updated_formatted = $this->date_updated->setTimezone(
                    new \DateTimeZone('Europe/Belgrade')
                )->format('d.m.Y.');
            } else {
                $date_updated_formatted = null;
            }
            return $date_updated_formatted;
        }

        return $this->{$fieldName};
    }

    public function __set($fieldName, $value) {
        $this->{$fieldName} = $value;
    }
}
