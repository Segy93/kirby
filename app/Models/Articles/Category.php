<?php

namespace App\Models\Articles;

use App\Providers\SEOService;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Articles__Categories")
 */
class Category implements \JsonSerializable {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string")
     */
    public $name;

    /**
     * @ORM\Column(type="string")
     */
    public $picture;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;


    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\Column(type="integer")
     */
    public $order_category;

    /**
     * @ORM\OneToMany(targetEntity="Article", mappedBy="category")
     */
    public $articles;

    /**
     * json Serilizacija
     * @return mixed
     */
    public function jsonSerialize(): mixed {
        return [
            'id'                =>  $this->id,
            'name'              =>  $this->name,
            'picture'           =>  $this->picture,
            'order_category'    =>  $this->order_category,
            'articles'          =>  $this->articles,
            'url'               =>  $this->url,
            'created_at'        =>  $this->created_at,
            'updated_at'        =>  $this->updated_at,
        ];
    }

    public function __get($fieldName) {
        if ($fieldName === 'url') {
            return SEOService::getSEO('articleCategory_' . $this->id)->url;
        }

        return $this->{$fieldName};
    }

    public function __set($fieldName, $value) {
        $this->{$fieldName} = $value;
    }
}
