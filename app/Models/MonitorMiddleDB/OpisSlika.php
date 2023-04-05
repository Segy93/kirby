<?php

namespace App\Models\MonitorMiddleDB;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="opis_slika")
 */
class OpisSlika {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id_opis_vrednost_slika;

    /**
     * @ORM\Column(type="integer")
     */
    protected $artid;

    /**
     * @ORM\Column(type="integer")
     */
    protected $rb;

    /**
     * @ORM\Column(type="string")
     */
    protected $fajl;

    public function __get($fieldName) {
        return $this->{$fieldName};
    }
}
