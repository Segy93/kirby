<?php

namespace App\Models\MonitorMiddleDB;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="monitor_artikli")
 */
class MonitorArtikal {

    /**
    * @ORM\Column(type="integer")
    */
    private $komparator;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $artid;

    /**
     * @ORM\Column(type="string")
     */
    private $artikal;

    /**
     * @ORM\Column(type="integer")
     */
    private $tipid;

    /**
     * @ORM\Column(type="float")
     */
    private $dealerdin;

    /**
     * @ORM\Column(type="float")
     */
    private $enduserdin;

    /**
     * @ORM\Column(type="float")
     */
    private $vaucer;

    /**
     * @ORM\Column(type="boolean")
     */
    private $f_magacin_stanje;

    /**
     * @ORM\Column(type="boolean")
     */
    private $f_radnja_stanje;

    /**
     * @ORM\Column(type="boolean")
     */
    private $f_preporucena_mp;

    /**
     * @ORM\Column(type="boolean")
     */
    private $f_akcija;

    /**
     * @ORM\Column(type="boolean")
     */
    private $f_istaknut;

    /**
     * @ORM\Column(type="boolean")
     */
    private $f_presales;

    /**
     * @ORM\Column(type="boolean")
     */
    private $f_published;

    /**
     * @ORM\Column(type="integer")
     */
    private $nid;

     /**
      * @ORM\ManyToOne(targetEntity="MonitorKategorija", inversedBy="artikli")
      * @ORM\JoinColumn(name="tipid", referencedColumnName="tipid")
      */
    private $kategorija;

    /**
     * @ORM\OneToMany(targetEntity="MonitorOpisVrednost", mappedBy="artikal")
     */
    private $vrednosti;

    /**
     * @ORM\OneToOne(targetEntity="App\Models\Product")
     * @ORM\JoinColumn(name="artid", referencedColumnName="artid")
     */
    private $product;

    public function __get($fieldName) {
        return $this->{$fieldName};
    }
}
