<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\BannerService;
use App\Providers\ProductService;
use App\Providers\SearchService;
use App\Providers\ShopService;
use App\Providers\WishListService;

/**
 * Lista proizvoda koji se poklapaju sa traženim terminom
 */
class SearchList extends BaseComponent {
    protected $composite = true;
    private $limit              = 20;
    private $search             = '';
    protected $css       = ['SearchList/css/SearchList.css'];
    protected $js        = ['SearchList/js/SearchList.js'];

    private $products           = [];
    private $product_single     = null;

    public function __construct($product_single = null, $search = '') {
        $this->products         = SearchService::searchProductsByName($search, 20);
        $this->product_single   = $product_single;
        $this->search           = $search;

        if ($product_single !== null) {
            parent::__construct([$product_single]);
        }
    }

    public function renderHTML() {
        $args = [
            'banner'            => BannerService::getBannerSearchPage($this->search),
            'products'          => $this->products,
            'product_single'    => $this->product_single,
            'search'            => $this->search,
        ];
        return view('SearchList/templates/SearchList', $args);
    }

    /**
     * Dohvatanje podataka za stranu pretrage
     *
     * @param   array   $params             Parametri prosleđeni AJAX-om
     *                  $params['last']     Poslednji dohvaćen proizvod
     *                  $params['search']   Šta je korisnik kucao u pretrazi
     * @return  array                       Podaci neophodni za render liste
     */
    public function fetchData(array $params): array {
        $last           = $params['last'];
        $last           = is_numeric($last) ? intval($last) : $last;
        $search         = $params['search'];

        return [
            'cart'      => ShopService::getUserCartCurrent(),
            'compare'   => ProductService::getComparingProducts(),
            'wishlist'  => WishListService::getWishListCurrent(),
            'products'  => SearchService::searchProductsByName($search, $this->limit, $last),
        ];
    }
}
