<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Positions")
 */
class Position implements \JsonSerializable {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="integer")
     */
    public $page_type_id;

    /**
     * @ORM\Column(type="string")
     */
    public $position;

    /**
     * @ORM\Column(type="integer")
     */
    public $image_width;

    /**
     * @ORM\Column(type="integer")
     */
    public $image_height;

    /**
     * @ORM\OneToMany(targetEntity="Banner", mappedBy="position")
     */
    public $banners;

    /**
     * @ORM\ManyToOne(targetEntity="PageType")
     * @ORM\JoinColumn(name="page_type_id", referencedColumnName="id")
     */
    public $page_type;

    /**
     * json Serilizacija
     * @return void
     */
    public function jsonSerialize() {
        return [
            'id'                =>  $this->id,
            'page_type_id'      =>  $this->page_type_id,
            'position'          =>  $this->position,
            'image_width'       =>  $this->image_width,
            'image_height'      =>  $this->image_height,
            // 'banners'           =>  $this->banners,
            'page_type'         =>  $this->page_type,
        ];
    }

    public function __get($fieldName) {
        return $this->{$fieldName};
    }

    public function __set($fieldName, $value) {
        $this->{$fieldName} = $value;
    }
}
