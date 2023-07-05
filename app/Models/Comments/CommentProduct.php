<?php

namespace App\Models\Comments;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Comments__Product")
 */
class CommentProduct extends Comment {

    /**
     * @ORM\Column(type="integer")
     */
    public $product_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Models\Product", inversedBy="comments")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    public $product;



    /**
     * json Serilizacija
     * @return mixed
     */
    public function jsonSerialize(): mixed {
        $json = [
            'product_id'    =>  $this->product_id,
            'product'       =>  $this->product,
        ];

        return array_merge(parent::jsonSerialize(), $json);
    }

    protected $comment_type = 'product';

    public function __get($fieldName) {
        return $this->{$fieldName};
    }

    public function __set($fieldName, $value) {
        $this->{$fieldName} = $value;
    }
}
