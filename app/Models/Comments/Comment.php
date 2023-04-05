<?php

namespace App\Models\Comments;

use App\Providers\CommentService;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"article" = "CommentArticle", "product" = "CommentProduct"})
 * @ORM\Table(name="Comments__Main")
 */
abstract class Comment implements \JsonSerializable {
    public function repl() {
        echo get_class($this->replies->getValues());
        die;
    }

    public function formattedDate() {
        return $this->date->setTimezone(new \DateTimeZone('Europe/Belgrade'))->format('d.m.Y. H:i');
    }

    public function dateTime() {
        return $this->date->setTimezone(new \DateTimeZone('Europe/Belgrade'))->format('Y-m-d\TH:i:s');
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $user_id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $parent_id;

    /**
     * @ORM\Column(type="string")
     */
    protected $text;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $approved;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Models\User", inversedBy="addresses")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Models\Comments\Comment", mappedBy="parent_id")
     */
    protected $replies;

    /**
     * json Serilizacija
     * @return void
     */
    public function jsonSerialize() {
        return [
            'id'             =>  $this->id,
            'user_id'        =>  $this->user_id,
            'parent_id'      =>  $this->parent_id,
            'text'           =>  $this->text,
            'approved'       =>  $this->approved,
            'date'           =>  $this->date,
            'user'           =>  $this->user,
            'replies'        =>  CommentService::getAllReplies($this->id, $this->comment_type),
            'timestamp'      =>  $this->date->getTimestamp(),
            'date_formatted' =>  $this->formattedDate(),
            'date_time'      =>  $this->dateTime(),
        ];
    }

    public function __construct() {
        $this->approved     =   0;
        $this->date         =   new \DateTime();
    }

    public function __get($fieldName) {
        return $this->{$fieldName};
    }

    public function __set($fieldName, $value) {
        $this->{$fieldName} = $value;
    }
}
