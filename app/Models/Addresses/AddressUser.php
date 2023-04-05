<?php

namespace App\Models\Addresses;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Addresses__User")
 */
class AddressUser extends Address {

    /**
     * @ORM\Column(type="integer")
     */
    public $user_id;

    /**
     * @ORM\Column(type="string")
     */
    public $contact_name;

    /**
     * @ORM\Column(type="string")
     */
    public $contact_surname;

    /**
     * @ORM\Column(type="string")
     */
    public $company;

    /**
     * @ORM\Column(type="integer")
     */
    public $pib;

    /**
     * @ORM\Column(type="string")
     */
    public $phone_nr;

    /**
     * @ORM\Column(type="boolean")
     */
    public $preferred_address_delivery;

    /**
     * @ORM\Column(type="boolean")
     */
    public $preferred_address_billing;

    /**
     * @ORM\ManyToOne(targetEntity="App\Models\User", inversedBy="addresses")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    public $user;

    protected $address_type = 'user';

    /**
     * @ORM\Column(type="integer")
     */
    public $status;

    private function getAddressStatus($status) {
        $statuses = [
            0   =>  'unused',
            1   =>  'used',
            2   =>  'deleted',
        ];

        return array_key_exists($status, $statuses) ? $statuses[$status] : null;
    }

    private function setAddressStatus($status) {
        $statuses = [
            'unused'    =>  0,
            'used'      =>  1,
            'deleted'   =>  2,
        ];

        $this->status = array_key_exists($status, $statuses) ? $statuses[$status] : 0;
    }

    /**
     * json Serilizacija
     * @return void
     */
    public function jsonSerialize() {
        $json = [
            'user_id'                       =>  $this->user_id,
            'contact_name'                  =>  $this->contact_name,
            'contact_surname'               =>  $this->contact_surname,
            'company'                       =>  $this->company,
            'phone_nr'                      =>  $this->phone_nr,
            'city'                          =>  $this->city,
            'address'                       =>  $this->address,
            'postal_code'                   =>  $this->postal_code,
            'preferred_address_delivery'    =>  $this->preferred_address_delivery,
            'preferred_address_billing'     =>  $this->preferred_address_billing,
            'status'                        =>  $this->status,
            'address_type'                  =>  $this->address_type,
            'pib'                           =>  $this->pib,
        ];

        return array_merge(parent::jsonSerialize(), $json);
    }


    public function __get($fieldName) {
        return $this->{$fieldName};
    }

    public function __set($fieldName, $value) {
        $this->{$fieldName} = $value;
    }
}
