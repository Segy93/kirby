<?php

namespace App\Models\Comments;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Comments__Article")
 */
class CommentArticle extends Comment {
    /**
     * @ORM\Column(type="integer")
     */
    public $article_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Models\Articles\Article", inversedBy="comments")
     * @ORM\JoinColumn(name="article_id", referencedColumnName="id")
     */
    public $article;

    /**
     * json Serilizacija
     * @return void
     */
    public function jsonSerialize() {
        $json = [
            'article_id'    =>  $this->article_id,
            'article'       =>  $this->article,
        ];

        return array_merge(parent::jsonSerialize(), $json);
    }

    protected $comment_type = 'article';

    public function __get($fieldName) {
        return $this->{$fieldName};
    }

    public function __set($fieldName, $value) {
        $this->{$fieldName} = $value;
    }
}
