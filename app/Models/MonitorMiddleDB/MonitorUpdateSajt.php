<?php

namespace App\Models\MonitorMiddleDB;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="monitor_update_sajt")
 */
class MonitorUpdateSajt {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $artid;

    /**
     * @ORM\Column(type="string")
     */
    protected $kljuc;

    /**
     * @ORM\Column(type="string")
     */
    protected $vrednost;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $f_obrisano;

    /**
     * @ORM\ManyToOne(targetEntity="MonitorArtikal")
     * @ORM\JoinColumn(name="artid", referencedColumnName="artid")
     */
    protected $artikal;

    public function __get($fieldName) {
        return $this->{$fieldName};
    }
}
