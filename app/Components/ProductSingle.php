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
    protected $configurator_add_button = null;
    private $show_link          = true;
    private $is_configurator    = false;
    private $configuration_name = null;



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
     * @param boolean $is_configurator          menja korisnicke kontrole ukoliko je konfigurator
     * @param string  $configuration_name       Naziv konfiguracije
     */
    public function __construct(
        bool $show_link = true,
        bool $is_configurator = false,
        string $configuration_name = 'trenutni'
    ) {
        $button_mode = 'full';
        $this->cartToggle               = new AtomCartToggle($button_mode);
        $this->productCompare           = new CompareProductsToggle();
        $this->productRating            = new ProductRating();
        $this->wishListToggle           = new AtomWishListToggle($button_mode);
        $this->configurator_add_button  = new ConfiguratorAddButton();
        $this->show_link                = $show_link;
        $this->is_configurator          = $is_configurator;
        $this->configuration_name       = $configuration_name;
        parent::__construct([
            $this->wishListToggle,
            $this->cartToggle,
            $this->productRating,
            $this->productCompare,
            $this->configurator_add_button,
        ]);
        ProductService::enableImageFormatThumbnail();
    }

    public function renderHTML($product = null) {
        $tommorow = new \DateTime('tomorrow');
        $configuration_id = null;
        if ($this->configuration_name !== ConfigurationService::$reserved_name) {
            $configuration_id = ConfigurationService::getConfigurationIdByName($this->configuration_name);
        }
        $args = [
            'product'                   =>  $product,
            'isLogged'                  =>  UserService::isUserLoggedIn(),
            'wishListToggle'            =>  $this->wishListToggle,
            'cartToggle'                =>  $this->cartToggle,
            'productRating'             =>  $this->productRating,
            'productCompare'            =>  $this->productCompare,
            'configurator_add_button'   =>  $this->configurator_add_button,
            'js_template'               =>  $product === null,
            'show_link'                 =>  $this->show_link,
            'is_configurator'           =>  $this->is_configurator,
            'configuration_id'          =>  $configuration_id,
            'tommorow'                  =>  $tommorow->format('Y-m-d H:i:s'),
            'protocol'                  =>  BaseService::getProtocol(),
            'configuration_name'        =>  $this->configuration_name,
        ];

        return view('ProductSingle/templates/ProductSingle', $args);
    }
}
