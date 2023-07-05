<?php

namespace App\Models\StaticPages;
use App\Providers\SEOService;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="StaticPages__Categories")
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
     * @ORM\Column(type="datetime")
     */
    private $created_at;


    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity="Page", mappedBy="category")
     */
    public $pages;

    public function jsonSerialize(): mixed {
        return [
            'id'                =>  $this->id,
            'name'              =>  $this->name,
            'pages'             =>  $this->pages,
            'created_at'        =>  $this->created_at,
            'updated_at'        =>  $this->updated_at,
            'url'               =>  $this->url,
        ];
    }

    public function __get($fieldName) {
        if ($fieldName === 'url') {
            $seo = SEOService::getSEO('static_category_' . $this->id);
            return $seo !== null ? $seo->url : null;
        }

        return $this->{$fieldName};
    }

    public function __set($fieldName, $value) {
        $this->{$fieldName} = $value;
    }
}
