<?php

namespace App\Models\Articles;

use App\Providers\SEOService;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Articles__Main")
 */
class Article implements \JsonSerializable {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $category_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $author_id;

    /**
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @ORM\Column(type="string")
     */
    private $text;

    /**
     * @ORM\Column(type="string")
     */
    private $excerpt;

    /**
     * @ORM\Column(type="string")
     */
    private $picture;

    /**
     * @ORM\Column(type="integer")
     */
    private $views;

    /**
     * @ORM\Column(type="datetime")
     */
    private $published_at;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

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
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="App\Models\Admin", inversedBy="articles")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    private $author;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="articles")
     * @ORM\JoinTable(name="Articles__ArticleTags")
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity="App\Models\Comments\CommentArticle", mappedBy="article")
     */
    private $comments;

    /**
     * Dohvata vrednost statusa po opisu
     * @param   string      $status     Opis statusa
     * @return  int         Vraća status_code
     */
    public static function getStatusValues($status_description) {
        $statuses = [
            'published' =>  0,
            'draft'     =>  1,
        ];

        return $statuses[$status_description];
    }

    /**
     * Dohvata opis statusa po vrednosti
     * @param   int         $status_code    Vrednost statusa
     * @return  string      Vraća description
     */
    private static function getStatusDescriptions($status_code) {
        $status_descriptions = [
            0   =>  'published',
            1   =>  'draft',
        ];

        return $status_descriptions[$status_code];
    }

    /**
     * json Serilizacija
     * @return mixed
     */
    public function jsonSerialize(): mixed {
        return [
            'id'                =>  $this->id,
            'category_id'       =>  $this->category_id,
            'author_id'         =>  $this->author_id,
            'title'             =>  $this->title,
            'text'              =>  $this->text,
            'excerpt'           =>  $this->excerpt,
            'picture'           =>  $this->picture,
            'views'             =>  $this->views,
            'status'            =>  $this->status,
            'category'          =>  $this->category,
            'author'            =>  $this->author,
            'tags'              =>  $this->tags,
            'url'               =>  $this->url,
            'published_at'      =>  $this->published_at->format('d.m.Y'),
            'published_unmod'   =>  $this->published_at->format('Y-m-d'),
            'published_full'   =>  $this->published_at->format('Y-m-d H:i:s'),
            'created_at'        =>  $this->created_at,
            'updated_at'        =>  $this->updated_at,
        ];
    }

    public function __construct() {
        $this->views        =   0;
        $this->published_at =   new \DateTime();
        $this->status       =   1;
    }

    public function __get($fieldName) {
        if ($fieldName === 'url') {
            return SEOService::getSEO('article_' . $this->id)->url;
        }

        if ($fieldName === 'published') {
            return self::getStatusDescriptions($this->status) === 'published';
        }

        if ($fieldName === 'draft') {
            return self::getStatusDescriptions($this->status) === 'draft';
        }

        return $this->{$fieldName};
    }

    public function __set($fieldName, $value) {
        if ($fieldName === 'published') {
            $this->status = $this->getStatusValues('published');
        }

        if ($fieldName === 'draft') {
            $this->status = $this->getStatusValues('draft');
        }

        $this->{$fieldName} = $value;
    }
}
