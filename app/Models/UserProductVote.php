<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="UserProductVote")
 */
class UserProductVote {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="integer")
     */
    public $product_id;

    /**
     * @ORM\Column(type="integer")
     */
    public $user_id;

    /**
     * @ORM\Column(type="integer")
     */
    public $vote;

    /**
     * @ORM\Column(type="binary")
     */
    public $ip_address;

    public function __get($fieldName) {
        return $this->{$fieldName};
    }

    public function __set($fieldName, $value) {
        $this->{$fieldName} = $value;
    }
}
