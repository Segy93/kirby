<?php

namespace App\Models;

use App\Providers\SEOService;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="SEO")
 */
class SEO {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string")
     */
    public $machine_name;

    /**
     * @ORM\Column(type="string")
     */
    public $keywords;

    /**
     * @ORM\Column(type="string")
     */
    public $description;

    /**
     * @ORM\Column(type="string")
     */
    public $title;

    /**
     * @ORM\Column(type="string")
     */
    public $thumbnail_twitter;

    /**
     * @ORM\Column(type="string")
     */
    public $image_twitter;

    /**
     * @ORM\Column(type="string")
     */
    public $image_open_graph;

    /**
     * @ORM\Column(type="string")
     */
    public $url;

    public function __get($fieldName) {
        if ($fieldName === 'twitter_handle_publisher') {
            return '@monitor_com';
        }
        if ($fieldName === 'twitter_handle_author') {
            return '@monitor_com';
        }

        if ($fieldName === 'thumbnail_twitter' && $this->thumbnail_twitter === null) {
            SEOService::generateImages($this->machine_name);
        }

        if ($fieldName === 'image_twitter' && $this->image_twitter === null) {
            SEOService::generateImages($this->machine_name);
        }

        if ($fieldName === 'image_open_graph' && $this->image_open_graph === null) {
            SEOService::generateImages($this->machine_name);
        }
        return $this->{$fieldName};
    }

    public function __set($fieldName, $value) {
        $this->{$fieldName} = $value;
    }
}
