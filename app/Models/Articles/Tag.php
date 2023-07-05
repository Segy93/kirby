<?php

namespace App\Models\Articles;

use App\Providers\SEOService;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Articles__Tags")
 */
class Tag implements \JsonSerializable {
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
     * @ORM\ManyToMany(targetEntity="Article", mappedBy="tags")
     */
    public $articles;

    /**
     * json Serilizacija
     * @return mixed
     */
    public function jsonSerialize(): mixed {
        return [
            'id'    =>  $this->id,
            'name'  =>  $this->name,
            'url'   =>  $this->url,
        ];
    }

    public function __get($fieldName) {
        if ($fieldName === 'url') {
            return SEOService::getSEO('tag_' . $this->id)->url;
        }

        return $this->{$fieldName};
    }

    public function __set($fieldName, $value) {
        $this->{$fieldName} = $value;
    }
}
