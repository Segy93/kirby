<?php

namespace App\Components;

use App\Components\AtomProductDescription;
use App\Components\AtomProductDetails;
use App\Components\AtomProductName;
use App\Components\CompareProductButton;
use App\Components\InfoHelpDesk;
use App\Components\ProductLightboxGallery;
use App\Components\ProductSingle;
use App\Components\ProductStock;
use App\Components\ProductVideo;
use App\Components\RelatedProducts;
use App\Components\SocialShare;
use App\Components\Tabs;

use App\Providers\ProductService;
use App\Providers\UserService;
use App\Models\Product;

/**
 *
 */
class ProductPage extends BaseComponent {
    protected $css = ['ProductPage/css/ProductPage.css'];
    protected $js = ['ProductPage/js/ProductPage.js'];
    protected $composite = true;

    private $id = null;
    private $product = null;

    private $breadcrumbs = null;
    private $comment_list = null;
    private $compareButton = null;
    private $contact_experts = null;
    private $description = null;
    private $details = null;
    private $gallery = null;
    private $infoShippingCost = null;
    private $name = null;
    private $product_video = null;
    private $productSingle = null;
    private $related = null;
    private $social_share = null;
    private $stock = null;
    private $tabs = null;
    private $banner = null;

    public function __construct(
        int $product_id,
        $breadcrumbs = null,
        $comment_list = null,
        $active_tab = '',
        $is_configurator = false,
        string $configuration_name = 'trenutni'
    ) {
        $this->id               = $product_id;
        Product::enableImageFormatFullWidth();
        $this->product          = ProductService::getProductByArtid($this->id);

        $product_id = $this->product->id;
        $category_id = $this->product->category_id;
        $price = $this->product->price_discount;

        $this->compareButton    = new CompareProductButton();
        $this->contact_experts  = new InfoHelpDesk();
        $this->description      = new AtomProductDescription();
        $this->details          = new AtomProductDetails();
        $this->gallery          = new ProductLightboxGallery();
        $this->infoShippingCost = new InfoShippingCost();
        $this->name             = new AtomProductName();
        $this->product_video    = new ProductVideo();
        $this->productSingle    = new ProductSingle(false, $is_configurator, $configuration_name);
        $this->related          = new RelatedProducts($category_id, $product_id, $price);
        $this->social_share     = new SocialShare();
        $this->stock            = new ProductStock();
        $this->banner           = new Banner('Ispod podataka');
        $this->comment_list     = $comment_list;


        $tabs = [
            [
                'label' => 'Specifikacije',
                'component' => $this->details,
                'has_notifications' => false,
            ],
            [
                'label' => 'Slični proizvodi',
                'component' => $this->related,
                'has_notifications' => false,
            ],
        ];

        $description = $this->product->description;
        $video = $this->product->youtube;

        if ($description !== null || strlen($description) !== 0) {
            array_push($tabs, [
                'label' => 'Opis',
                'component' => $this->description,
                'has_notifications' => false,
            ]);
        }

        if ($video !== null || strlen($video) !== 0) {
            array_push($tabs, [
                'label' => 'Video',
                'component' => $this->product_video,
                'has_notifications' => false,
            ]);
        }


        if ($comment_list !== null) {
            array_push($tabs, [
                'label' => 'Komentari',
                'component' => $this->comment_list,
                'has_notifications' => false,
            ]);
        }

        $this->tabs = new Tabs($tabs, $active_tab);
        $parent_construct = [
            $this->gallery,
            $this->details,
            $this->description,
            $this->name,
            $this->related,
            $this->compareButton,
            $this->stock,
            $this->productSingle,
            $this->contact_experts,
            $this->product_video,
            $this->comment_list,
            $this->social_share,
            $this->infoShippingCost,
        ];

        if ($this->tabs !== null) {
            $parent_construct [] = $this->tabs;
        }

        if ($this->breadcrumbs !== null) {
            $parent_construct [] = $this->breadcrumbs;
        }

        parent::__construct($parent_construct);
    }

    public function renderHTML() {
        $child_args = [
            $this->product,
            null,
        ];

        $description = $this->product->description;
        $video = $this->product->youtube;

        if ($description !== null || strlen($description) !== 0) {
            $child_args[] = $description;
        }

        if ($video !== null || strlen($video) !== 0) {
            $child_args[] = $video;
        }

        $child_args[] = $this->comment_list;
        $args = [
            'product'           => $this->product,
            'isLogged'          => UserService::isUserLoggedIn(),
            'child_args'        => $child_args,

            'breadcrumbs'       => $this->breadcrumbs,
            'comment_list'      => $this->comment_list,
            'compareButton'     => $this->compareButton,
            'contact_experts'   => $this->contact_experts,
            'description'       => $this->description,
            'details'           => $this->details,
            'gallery'           => $this->gallery,
            'infoShippingCost'  => $this->infoShippingCost,
            'name'              => $this->name,
            'product_video'     => $this->product_video,
            'productSingle'     => $this->productSingle,
            'social_share'      => $this->social_share,
            'stock'             => $this->stock,
            'tabs'              => $this->tabs,
            'banner'            => $this->banner
        ];

        return view('ProductPage/templates/ProductPage', $args);
    }
}
