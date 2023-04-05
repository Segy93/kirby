<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Banners")
 */
class Banner implements \JsonSerializable {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="integer")
     */
    public $position_id;

    /**
     * @ORM\Column(type="string")
     */
    public $title;

    /**
     * @ORM\Column(type="string")
     */
    public $image;

    /**
     * @ORM\Column(type="string")
     */
    public $link;

    /**
     * @ORM\Column(type="string")
     */
    public $urls;

    /**
     * @ORM\Column(type="integer")
     */
    public $nr_clicks = 0;

    /**
     * @ORM\Column(type="boolean")
     */
    public $status;

    /**
     * @ORM\ManyToOne(targetEntity="Position")
     * @ORM\JoinColumn(name="position_id", referencedColumnName="id")
     */
    public $position;

    /**
     * Pozicija banera iznad rezultata pretrage
     *
     * @var string
     */
    public static $POSITION_SEARCH_ABOVE = 'Iznad rezultata';

    /**
     * json Serilizacija
     * @return void
     */
    public function jsonSerialize() {
        return [
            'id'                =>  $this->id,
            'position_id'       =>  $this->position_id,
            'title'             =>  $this->title,
            'image'             =>  $this->image,
            'link'              =>  $this->link,
            'urls'              =>  $this->urls,
            'nr_clicks'         =>  $this->nr_clicks,
            'status'            =>  $this->status,
            'position'          =>  $this->position,
        ];
    }

    public function __construct() {
        $this->status   =   0;
    }

    public function __get($fieldName) {
        return $this->{$fieldName};
    }

    public function __set($fieldName, $value) {
        $this->{$fieldName} = $value;
    }
}
