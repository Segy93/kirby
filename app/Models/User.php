<?php

namespace App\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"local" = "UserLocal"})
 * @ORM\Table(name="Users")
 */
abstract class User implements \JsonSerializable {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="string")
     */
    protected $surname;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $registration_date;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $last_visited;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $banned;

    /**
     * @ORM\Column(type="string")
     */
    protected $phone_nr;

    /**
     * @ORM\Column(type="integer")
     */
    protected $status;

    /**
     * @ORM\OneToMany(targetEntity="WishList", mappedBy="user")
     */
    protected $wish_list;

    /**
     * @ORM\OneToMany(targetEntity="Cart", mappedBy="user")
     */
    protected $cart;

    /**
     * @ORM\OneToMany(targetEntity="Order", mappedBy="user")
     */
    protected $orders;

    /**
     * @ORM\OneToMany(targetEntity="App\Models\Addresses\AddressUser", mappedBy="user")
     */
    protected $addresses;

    /**
     * @ORM\OneToMany(targetEntity="App\Models\Configuration\ConfigurationMain", mappedBy="user")
     */
    protected $configurations;

    /**
     * @ORM\OneToOne(targetEntity="UserLocal")
     * @ORM\JoinColumn(name="id", referencedColumnName="id")
     */
    protected $local;

    /**
     * json Serilizacija
     * @return  array
     */
    public function jsonSerialize() {
        return [
            'id'                =>  $this->id,
            'name'              =>  $this->name,
            'surname'           =>  $this->surname,
            'registration_date' =>  $this->registration_date,
            'last_visited'      =>  $this->last_visited,
            'banned'            =>  $this->banned,
            'phone_nr'          =>  $this->phone_nr,
            'status'            =>  $this->status,
            'wish_list'         =>  $this->wish_list->getValues(),
            'orders'            =>  $this->orders,
            'addresses'         =>  $this->addresses->getValues(),
        ];
    }

    public function __construct() {
        $this->registration_date    =   new \DateTime();
        $this->status               =   0;
        $this->wish_list            =   new ArrayCollection();
        $this->cart                 =   new ArrayCollection();
        $this->orders               =   new ArrayCollection();
        $this->addresses            =   new ArrayCollection();
    }

    public function __get($fieldName) {
        return $this->{$fieldName};
    }

    public function __set($fieldName, $value) {
        $this->{$fieldName} = $value;
    }
}
