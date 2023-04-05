<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\WishListService;

/**
 *
 */
class MainMenuWishList extends BaseComponent {
    protected $js = ['MainMenuWishList/js/MainMenuWishList.js'];

    public function renderHTML() {
        $args = [
            'wishList' => count($this->getUsersWishlist()),
        ];

        return view('MainMenuWishList/templates/MainMenuWishList', $args);
    }



    /*CREATE*/










    /*READ*/

    private function getUsersWishlist() {
        $wish = array();

        $wishList = WishListService::getWishListByUserId();

        if (!empty($wishList)) {
            foreach ($wishList as $element) {
                array_push($wish, $element->id);
            }
        }

        return $wish;
    }




    public function fetchData() {
        return count($this->getUsersWishlist());
    }
}
