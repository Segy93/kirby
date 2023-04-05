<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\StaticPageService;
use Illuminate\Support\Facades\Request;

/**
 *
 */
class StaticCategory extends BaseComponent {
    private $params = null;
    private $type = 'page';
    protected $css  = ['StaticCategory/css/StaticCategory.css'];

    public function __construct($params, $type) {
        $this->params = $params;
        $this->type = $type;
    }

    public function renderHTML() {
        if ($this->type === 'page') {
            $page  = StaticPageService::getPageById($this->params);
            $links = StaticPageService::getAllPagesByCategoryId($page->category_id);
        } else {
            $links = StaticPageService::getAllPagesByCategoryId($this->params);
            $page = !empty($links) ? $links[0] : null;
        }

        $args = [
            'page'  => $page,
            'type'  => $this->type,
            'links' => $links,
            'path'  => Request::path(),
        ];

        return view('StaticCategory/templates/StaticCategory', $args);
    }
}
