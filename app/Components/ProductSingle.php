<?php

namespace App\Components;

use App\Components\AtomCartToggle;
use App\Components\AtomWishListToggle;
use App\Components\CompareProductsToggle;

use App\Providers\BaseService;
use App\Providers\ConfigurationService;
use App\Providers\ProductService;
use App\Providers\UserService;


/**
 *
 */
class ProductSingle extends BaseComponent {
    protected $composite        = true;

    protected $wishListToggle   = null;
    protected $cartToggle       = null;
    protected $productRating    = null;
    protected $productCompare   = null;
    private $show_link          = true;



    protected $css = [
        'ProductSingle/css/ProductSingle.css',
    ];
    protected $js  = [
        'ProductSingle/js/ProductSingle.js',
    ];


    /**
     * Kreira instance svih potrebnih komponenti za rad
     * cuva lokalno sve potrebne podatke za rad
     *
     * @param boolean $show_link                prikazuje link ka proizvodu ukoliko je strana kategorije, sakriva ga u suprotnom
     */
    public function __construct(bool $show_link = true) {
        $button_mode = 'full';
        $this->cartToggle               = new AtomCartToggle($button_mode);
        $this->productCompare           = new CompareProductsToggle();
        $this->productRating            = new ProductRating();
        $this->wishListToggle           = new AtomWishListToggle($button_mode);
        $this->show_link                = $show_link;
        parent::__construct([
            $this->wishListToggle,
            $this->cartToggle,
            $this->productRating,
            $this->productCompare,
        ]);
        ProductService::enableImageFormatThumbnail();
    }

    public function renderHTML($product = null) {
        $tommorow = new \DateTime('tomorrow');
        $args = [
            'product'                   =>  $product,
            'isLogged'                  =>  UserService::isUserLoggedIn(),
            'wishListToggle'            =>  $this->wishListToggle,
            'cartToggle'                =>  $this->cartToggle,
            'productRating'             =>  $this->productRating,
            'productCompare'            =>  $this->productCompare,
            'js_template'               =>  $product === null,
            'show_link'                 =>  $this->show_link,
            'tommorow'                  =>  $tommorow->format('Y-m-d H:i:s'),
            'protocol'                  =>  BaseService::getProtocol(),
        ];

        return view('ProductSingle/templates/ProductSingle', $args);
    }
}
