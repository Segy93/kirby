<?php

namespace App\Models\MonitorMiddleDB;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="monitor_opis_kategorije")
 */
class MonitorOpisKategorija {
    /**
     * @ORM\Column(type="integer")
     */
    public $tipid;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id_opis_kljuc;

    /**
     * @ORM\Column(type="integer")
     */
    public $redni_broj;

    /**
     * @ORM\Column(type="string")
     */
    public $kljuc_naziv;

    /**
     * @ORM\Column(type="string")
     */
    public $monitor_kategoirja_naziv;

    /**
     * @ORM\ManyToOne(targetEntity="MonitorKategorija", inversedBy="opis")
     * @ORM\JoinColumn(name="tipid", referencedColumnName="tipid")
     */
    public $kategorija;

    /**
     * @ORM\OneToOne(targetEntity="MonitorOpisVrednost", mappedBy="opis_kategorija")
     */
    public $opis_vrednost;

    public function __get($fieldName) {
        return $this->{$fieldName};
    }
}
