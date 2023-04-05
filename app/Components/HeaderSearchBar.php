<?php

namespace App\Components;

use App\Providers\SearchService;
use App\Providers\SessionService;

/**
*
*/
class HeaderSearchBar extends BaseComponent {

    protected $css  = [
        'HeaderSearchBar/css/auto-complete.css',
        'HeaderSearchBar/css/HeaderSearchBar.css'
    ];
    protected $js   = [
        'HeaderSearchBar/js/auto-complete.js',
        'HeaderSearchBar/js/HeaderSearchBar.js'
    ];

    public function renderHTML() {
        $args = [
            'csrf_field'         => SessionService::getCsrfField(),
        ];
        return view('HeaderSearchBar/templates/HeaderSearchBar', $args);
    }








    /**
    *   $params["query"] string za pretragu
    */
    public function getSearchResults($params) {
        $query      = $params["query"];
        $results    = SearchService::searchProductsByName($query);
        $formated   = [];

        foreach ($results as $result) {
            $item = [];
            $item ['url']  = $result->url;
            $item ['name'] = $result->name;
            array_push($formated, $item);
        }

        return $formated;
    }
}
