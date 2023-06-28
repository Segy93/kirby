<?php

namespace App\Models\Articles;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Articles__ArticleTags")
 */
class ArticleTag implements \JsonSerializable {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $article_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $tag_id;

    /**
     * json Serilizacija
     * @return mixed
     */
    public function jsonSerialize(): mixed {
        return [
            'id'            =>  $this->id,
            'article_id'    =>  $this->article_id,
            'tag_id'        =>  $this->tag_id,
        ];
    }

    public function __get($fieldName) {
        return $this->{$fieldName};
    }

    public function __set($fieldName, $value) {
        $this->{$fieldName} = $value;
    }
}
