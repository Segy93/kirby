<?php

namespace App\Models\MonitorMiddleDB;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="monitor_opis_vrednosti")
 */
class MonitorOpisVrednost {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id_vrednosti;

    /**
     * @ORM\Column(type="integer")
     */
    public $id_opis_kljuc;

    /**
     * @ORM\Column(type="string")
     */
    public $vrednost;

    /**
     * @ORM\Column(type="integer")
     */
    public $artid;

    /**
     * @ORM\OneToOne(targetEntity="MonitorOpisKategorija", inversedBy="opis_vrednost")
     * @ORM\JoinColumn(name="id_opis_kljuc", referencedColumnName="id_opis_kljuc")
     */
    public $opis_kategorija;

    /**
     * @ORM\ManyToOne(targetEntity="MonitorArtikal", inversedBy="vrednosti")
     * @ORM\JoinColumn(name="artid", referencedColumnName="artid")
     */
    public $artikal;

    public function __get($fieldName) {
        return $this->{$fieldName};
    }
}
