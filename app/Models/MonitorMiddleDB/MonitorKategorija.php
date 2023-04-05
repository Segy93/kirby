<?php

namespace App\Models\MonitorMiddleDB;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="monitor_kategorije")
 */
class MonitorKategorija {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $tipid;

    /**
     * @ORM\Column(type="integer")
     */
    private $br;

    /**
     * @ORM\Column(type="string")
     */
    private $tip;

    /**
     * ORM\Column(type="string")
     */
    private $rem;

    /**
     * ORM\Column(type="string")
     */
    private $serno;

    /**
     * ORM\Column(type="integer")
     */
    private $special;

    /**
     * ORM\Column(type="integer")
     */
    private $komp;

    /**
     * ORM\Column(type="datetime")
     */
    private $changed;

    /**
     * ORM\Column(type="integer")
     */
    private $porez;

    /**
     * @ORM\OneToMany(targetEntity="MonitorArtikal", mappedBy="kategorija")
     */
    private $artikli;

    /**
     * @ORM\OneToMany(targetEntity="MonitorOpisKategorija", mappedBy="kategorija")
     */
    private $opis;

    public function __get($fieldName) {
        return $this->{$fieldName};
    }
}
