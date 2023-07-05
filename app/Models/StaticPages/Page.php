<?php

namespace App\Models\StaticPages;

use App\Providers\SEOService;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="StaticPages__Main")
 */
class Page implements \JsonSerializable {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="integer")
     */
    public $category_id;

    /**
     * @ORM\Column(type="string")
     */
    public $title;

    /**
     * @ORM\Column(type="string")
     */
    public $text;

    /**
     * @ORM\Column(type="integer")
     */
    public $order_page;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="articles")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    public $category;

    /**
     * json Serilizacija
     * @return mixed
     */
    public function jsonSerialize(): mixed {
        return [
            'id'                =>  $this->id,
            'category_id'       =>  $this->category_id,
            'title'             =>  $this->title,
            'text'              =>  $this->text,
            'order_page'        =>  $this->order_page,
            'category'          =>  $this->category,
            'created_at'        =>  $this->created_at,
            'updated_at'        =>  $this->updated_at,
            'url'               =>  $this->url,
        ];
    }

    public function __get($fieldName) {
        if ($fieldName === 'url') {
            $seo = SEOService::getSEObyMachineName('static_' . $this->id);
            return $seo !== null ? $seo->url : null;
        }

        return $this->{$fieldName};
    }

    public function __set($fieldName, $value) {
        $this->{$fieldName} = $value;
    }
}
