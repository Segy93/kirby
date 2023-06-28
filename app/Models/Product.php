<?php

namespace App\Models;

use App\Providers\ConfigService;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\Providers\SEOService;
use App\Providers\ProductService;

/**
 * @ORM\Entity
 * @ORM\Table(name="Products")
 */
class Product implements \JsonSerializable {

    //Visina thumbnail slike
    private $thumbnail_width = 120;

    //Širina thumbnail slike
    private $thumbnail_height = 120;

    private static $image_formats = [
        'thumbnail' => [200, 200],
        'full_width' => [1000, 1000],
    ];

    private static $images_attach = [
        'thumbnail' => false,
        'full_width' => false,
    ];

    private static function enableImageFormat($format) {
        self::$images_attach[$format] = true;
    }

    public static function enableImageFormatThumbnail() {
        self::enableImageFormat('thumbnail');
    }


    public static function enableImageFormatFullWidth() {
        self::enableImageFormat('full_width');
    }


    private function getImages() {
        $return = [];
        foreach (self::$images_attach as $format => $enabled) {
            if ($enabled === true) {
                list($width, $height) = self::$image_formats[$format];
                $return[$format] = ProductService::getPictures($this->artid, $width, $height);
            }
        }
        return $return;
    }

    public function inStock() {
        return $this->stock_warehouse > 0 || $this->shops->count() > 0;
    }

    public function inShop() {
        return $this->shops->count() > 0;
    }

    public function inWarehouse() {
        return $this->stock_warehouse > 0;
    }


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $artid;

    /**
     * @ORM\Column(type="integer")
     */
    private $category_id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $price_retail;

    /**
     * @ORM\Column(type="integer")
     */
    private $price_discount;

    /**
     * @ORM\Column(type="integer")
     */
    private $voucher;

    /**
     * @ORM\Column(type="integer")
     */
    private $rating_sum = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $rating_count = 0;


    /**
     * @ORM\Column(type="integer")
     */
    private $width = 0;


    /**
     * @ORM\Column(type="integer")
     */
    private $height = 0;


    /**
     * @ORM\Column(type="integer")
     */
    private $length = 0;


    /**
     * @ORM\Column(type="integer")
     */
    private $weight = 0;

    /**
     * @ORM\Column(type="boolean")
     */
    private $on_sale;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_featured;

    /**
     * @ORM\Column(type="boolean")
     */
    private $published;


    /**
     * @ORM\Column(type="boolean")
     */
    private $presales;

    /**
     * @ORM\Column(type="boolean")
     */
    private $stock_warehouse;

    /**
     * @ORM\Column(type="string")
     */
    private $ean;

    /**
     * @ORM\Column(type="string")
     */
    private $youtube;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;


    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;


    /**
     * @ORM\Column(type="string")
     */
    private $link;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="products")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="ProductPicture", mappedBy="product")
     */
    private $pictures;

    public function addAttributeValue(AttributeValue $attribute_value) {
        $this->attribute_values->add($attribute_value);
    }

    /**
     * Dohvata dodatne vrednosti za proizvod
     * @param   string      $order_by       po kom redosledu poređa polja opcionalno polje
     * @return  array       Vraća niz objekata AttributeValue
     */
    public function attribute_values($order_by = null) {
        return ProductService::getProductAttributes($this->id, $order_by);
    }

    /**
     * @ORM\ManyToMany(targetEntity="AttributeValue", cascade={"persist"})
     * @ORM\JoinTable(name="ProductAttributes",
     *      joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="attribute_value_id", referencedColumnName="id")}
     *      )
     */
    public $attribute_values;

    /**
     * @ORM\OneToMany(targetEntity="ProductAttribute", mappedBy="product")
     */
    public $product_attributes;

    /**
     * @ORM\ManyToMany(targetEntity="Shop")
     * @ORM\JoinTable(name="StockShops",
     *      joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="shop_id", referencedColumnName="id", unique=true)}
     * )
     */
    public $shops;

    /**
     * @ORM\OneToMany(targetEntity="WishList", mappedBy="product")
     */
    public $wishlist;

    /**
     * @ORM\OneToMany(targetEntity="Cart", mappedBy="product")
     */
    public $cart;

    /**
     * @ORM\OneToMany(targetEntity="App\Models\Comments\CommentProduct", mappedBy="product")
     */
    public $comments;

    private function getImagesThumbnail() {
        return ProductService::getPictures($this->artid, $this->thumbnail_width, $this->thumbnail_height);
    }

    /**
     * Mapira polja product modela za import
     * @return  array       Vraća niz sa mapiranjem
     */
    public static function getMappingImport() {
        return [
            [
                'name_import'   =>  'artid',
                'name_local'    =>  'artid',
            ],
            [
                'name_import'   =>  'artikal',
                'name_local'    =>  'name',
            ],
            [
                'name_import'   =>  'Prošireni opis',
                'name_local'    =>  'description',
            ],
            [
                'name_import'   =>  'enduserdin',
                'name_local'    =>  'price_discount',
            ],
            [
                'name_import'   =>  'dealerdin',
                'name_local'    =>  'price_retail',
                'type'          =>  'slider',
            ],
            [
                'name_import'   =>  'vaucer',
                'name_local'    =>  'voucher',
            ],
            [
                'name_import'   =>  'f_magacin_stanje',
                'name_local'    =>  'stock_warehouse',
                'type'          =>  'checkbox',
            ],
            [
                'name_import'   =>  'f_radnja_stanje',
                'name_local'    =>  'stock_shop',
                'type'          =>  'checkbox',
            ],
            [
                'name_import'   =>  'f_akcija',
                'name_local'    =>  'on_sale',
            ],
            [
                'name_import'   =>  'f_published',
                'name_local'    =>  'published',
            ],
            [
                'name_import'   =>  'f_istaknut',
                'name_local'    =>  'is_featured',
            ],
            [
                'name_import'   =>  'f_presales',
                'name_local'    =>  'presales',
            ],
            [
                'name_import'   =>  'f_published',
                'name_local'    =>  'status',
            ],
            [
                'name_import'   =>  'EAN kod',
                'name_local'    =>  'ean',
            ],
            [
                'name_import'   =>  'Youtube code',
                'name_local'    =>  'youtube',
            ],
            [
                'name_import'   =>  'Link proizvođača',
                'name_local'    =>  'link',
            ],
        ];
    }

    /**
     * Mapira polja product modela za update
     * @return  array       Vraća niz sa mapiranjem
     */
    public static function getMappingUpdate() {
        return [
            [
                'name_import'   =>  'artid',
                'name_local'    =>  'artid',
            ],
            [
                'name_import'   =>  'artikal',
                'name_local'    =>  'name',
            ],
            [
                'name_import'   =>  'Prošireni opis',
                'name_local'    =>  'description',
            ],
            [
                'name_import'   =>  'sell_price',
                'name_local'    =>  'price_retail',
            ],
            [
                'name_import'   =>  'list_price',
                'name_local'    =>  'price_discount',
            ],
            [
                'name_import'   =>  'vaucer',
                'name_local'    =>  'voucher',
            ],
            [
                'name_import'   =>  'f_stanje',
                'name_local'    =>  'stock_warehouse',
            ],
            [
                'name_import'   =>  'f_stanje',
                'name_local'    =>  'stock_shop',
            ],
            [
                'name_import'   =>  'f_akcija',
                'name_local'    =>  'on_sale',
            ],
            [
                'name_import'   =>  'f_published',
                'name_local'    =>  'published',
            ],
            [
                'name_import'   =>  'f_istaknut',
                'name_local'    =>  'is_featured',
            ],
            [
                'name_import'   =>  'f_presales',
                'name_local'    =>  'presales',
            ],
            [
                'name_import'   =>  'f_published',
                'name_local'    =>  'status',
            ],
            [
                'name_import'   =>  'EAN kod',
                'name_local'    =>  'ean',
            ],
            [
                'name_import'   =>  'Youtube code',
                'name_local'    =>  'youtube',
            ],
            [
                'name_import'   =>  'Link proizvođača',
                'name_local'    =>  'link',
            ],
        ];
    }

    /**
     * json Serilizacija
     * @return mixed
     */
    public function jsonSerialize(): mixed {

        return [
            'id'                            =>  $this->id,
            'artid'                         =>  $this->artid,
            'category_id'                   =>  $this->category_id,
            'name'                          =>  $this->name,
            'description'                   =>  $this->description,
            'price_retail'                  =>  $this->price_retail,
            'price_discount'                =>  $this->price_discount,
            'discount_format'               =>  $this->discount_format,
            'retail_format'                 =>  $this->retail_format,
            'url'                           =>  $this->url,
            'voucher'                       =>  $this->voucher,
            'rating_sum'                    =>  $this->rating_sum,
            'rating_count'                  =>  $this->rating_count,
            'rating'                        =>  $this->rating,
            'position'                      =>  $this->position,
            'on_sale'                       =>  $this->on_sale,
            'is_featured'                   =>  $this->is_featured,
            'presales'                      =>  $this->presales,
            'published'                     =>  $this->published,
            'stock_warehouse'               =>  $this->stock_warehouse,
            'ean'                           =>  $this->ean,
            'youtube'                       =>  $this->youtube,
            'link'                          =>  $this->link,
            'category'                      =>  $this->category,
            'pictures'                      =>  $this->pictures,
            'images'                        =>  $this->images,
            'attribute_values'              =>  $this->attribute_values,
            'attribute_values__category'    =>  $this->attribute_values__category,
            'in_stock'                      =>  $this->inStock(),
            'in_shop'                       =>  $this->inShop(),
            'in_warehouse'                  =>  $this->inWarehouse(),
            'weight'                        =>  $this->weight,
            'created_at'                    =>  $this->created_at,
            'updated_at'                    =>  $this->updated_at,
        ];
    }

    public function __construct() {
        $this->shops                =   new ArrayCollection();
        $this->comments             =   new ArrayCollection();
        $this->pictures             =   new ArrayCollection();
        $this->attribute_values     =   new ArrayCollection();
    }

    public function __get($fieldName) {
        if ($fieldName === 'url') {
            return SEOService::getSEObyMachineName('product_' . $this->artid)->url;
        }

        if ($fieldName === 'url_absolute') {
            return ConfigService::getBaseUrl() . $this->url;
        }

        if ($fieldName === 'weight__field') {
            return ProductService::getProductWeight($this->artid);
        }

        if ($fieldName === 'images') {
            return $this->getImages();
        }

        if ($fieldName === 'in_stock') {
            return $this->inStock();
        }

        if ($fieldName === 'in_shop') {
            return $this->inShop();
        }

        if ($fieldName === 'in_warehouse') {
            return $this->inWarehouse();
        }

        if ($fieldName === 'rating') {
            return $this->rating_count === 0
                ? number_format(5, 1)
                : number_format(($this->rating_sum / $this->rating_count), 1)
            ;
        }

        if ($fieldName === 'position') {
            return ProductService::getProductImagePosition($this->id);
        }

        if ($fieldName === 'attribute_values__category') {
            return ProductService::getProductAttributes($this->id, 'order_category');
        }

        if ($fieldName === 'discount_format') {
            return number_format($this->price_discount, 2, ',', '.');
        }

        if ($fieldName === 'retail_format') {
            return number_format($this->price_retail, 2, ',', '.');
        }

        if ($fieldName === 'attribute_values__order_product') {
            return ProductService::getProductAttributes($this->id, 'order_product');
        }

        return $this->{$fieldName};
    }

    public function __set($fieldName, $value) {
        $this->{$fieldName} = $value;
    }

    private static $ranges = [
        'dimensions' => [
            [
                'min' => 0,
                'max' => 300,
                'unit' => 'mm',
                'scale' => 1,
            ],
            [
                'min' => 300,
                'max' => 1500,
                'unit' => 'cm',
                'scale' => 10,
            ],
            [
                'min' => 1500,
                'max' => 100000,
                'unit' => 'm',
                'scale' => 100,
            ],
        ],

        'mass' => [
            [
                'min' => 0,
                'max' => 1000,
                'unit' => 'g',
                'scale' => 1,
            ],
            [
                'min' => 1000,
                'max' => 100000,
                'unit' => 'kg',
                'scale' => 1000,
            ],
        ],
    ];

    private function getDimensionsMin() {
        return min($this->width, $this->height, $this->length);
    }

    public function shouldShowDimensions() {
        return $this->getDimensionsMin() > 0;
    }

    private function getUnitRange($which, $value) {
        foreach (self::$ranges[$which] as $range) {
            if ($range['min'] < $value && $value <= $range['max']) {
                  return $range;
            }
        }

        return self::$ranges[$which][0];
    }

    private function getDimensionsRange() {
        $value = $this->getDimensionsMin();
        return $this->getUnitRange('dimensions', $value);
    }

    public function getDimensionsDisplay() {
        $range = $this->getDimensionsRange();
        $scale = $range['scale'];
        $unit = $range['unit'];
        $display_width = $this->width / $scale;
        $display_height = $this->height / $scale;
        $display_length = $this->length / $scale;
        return $display_width . ' x ' . $display_height . ' x ' . $display_length . $unit;
    }

    private function getWeightRange() {
        return $this->getUnitRange('mass', $this->weight);
    }

    public function getWeightDisplay() {
        $range = $this->getWeightRange();
        $scale = $range['scale'];
        $unit = $range['unit'];
        $display_weight = $this->weight / $scale;
        return $display_weight . $unit;
    }

    public function shouldShowWeight() {
        return $this->weight > 0;
    }
}
