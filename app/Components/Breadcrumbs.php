<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\ArticleService;

/**
*
*/
class Breadcrumbs extends BaseComponent {
    protected $composite = true;
    protected $css       = ['Breadcrumbs/css/Breadcrumbs.css'];
    protected $js        = ['Breadcrumbs/js/Breadcrumbs.js'];

    private $prequels = [];
    private $sequels  = [];

    public function __construct($type = null, $node_id = null) {
        if ($type === 'article') {
            $article = ArticleService::getByID($node_id);

            $this->prequels [$article->category->name] = $article->category->url;
        }
    }

    public function renderHTML() {
        $args = [
            'links' => $this->getLinks(),
        ];
        return view('Breadcrumbs/templates/Breadcrumbs', $args);
    }


    private function getLinks() {
        $path = $_SERVER['REQUEST_URI'];
        //$path = "/laptopovi/test/pera/zdera";
        $path = preg_replace('/\?.*/', '', $path);
        $path = preg_replace('/reset-lozinke\/.*/', 'reset-lozinke', $path);
        $path = urldecode($path);
        $links = [
            'Početna' => '/',
        ];

        if (!empty($this->prequels)) {
            foreach ($this->prequels as $label => $url) {
                $links[$label] = $url;
            }
        }

        $path = explode('/', $path);
        $last_link = '/';
        foreach ($path as $index => $link) {
            if ($link !== '') {
                $label = strpos($link, '@') === false ? ucfirst($link) : $link;
                $label = str_replace("-", " ", $label);
                $label = str_replace("zelja", "želja", $label);
                $label = str_replace("raficke", "rafičke", $label);
                $label = str_replace("aticne", "atične", $label);
                $label = str_replace("loce", "loče", $label);
                $label = str_replace("ancevi", "ančevi", $label);
                $label = str_replace("unjaci", "unjači", $label);
                $label = str_replace("vucnici", "vučnici", $label);
                $label = str_replace("acunari", "ačunari", $label);
                $label = str_replace("ucista", "ućišta", $label);
                $label = str_replace("pticki", "ptički", $label);
                $label = str_replace("redjaji", "ređaji", $label);
                $label = str_replace("vucne", "vučne", $label);
                $label = str_replace("tampaci", "tampači", $label);
                $label = str_replace("otrosni", "otrošni", $label);
                $label = str_replace("isevi", "iševi", $label);
                $label = str_replace("lusalice", "lušalice", $label);
                $label = str_replace("rezna", "režna", $label);
                $label = str_replace("lesevi", "leševi", $label);
                $label = str_replace("otrosacka", "otrošačka", $label);
                $label = str_replace("astita", "aštita", $label);
                $label = str_replace("citaci", "čitači", $label);
                $label = str_replace("dz", "dž", $label);
                $label = str_replace("dj", "đ", $label);
                $links[$label] = $last_link . $link . '/';
                $last_link .= $link . '/';
            }
        }


        if (!empty($this->sequels)) {
            foreach ($this->sequels as $label => $url) {
                $links[$label] = $url;
            }
        }
        return $links;
    }
}
