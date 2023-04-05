<?php

namespace App\Components;

use App\Components\BaseComponent;


/**
*
*/
class ArticleSingle extends BaseComponent {
    protected $composite = true;
    protected $css =    ['ArticleSingle/css/ArticleSingleContent.css'];
    protected $icons = ['ArticleSingle/templates/icons'];
    protected $social_share = null;



    public function __construct($social_share) {
        parent::__construct([$social_share]);
        $this->social_share = $social_share;
    }

    public function renderHTML($article = null) {
        $args = [
            'article'       => $article,
            'social_share'  => $this->social_share,
            'js_template'   => $article === null,
        ];

        return view('ArticleSingle/templates/ArticleSingleContent', $args);
    }
}
