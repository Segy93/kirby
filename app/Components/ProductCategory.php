<?php

namespace App\Components;

use App\Providers\BannerService;
use App\Providers\CategoryService;
use App\Providers\ProductService;
use App\Providers\ShopService;
use App\Providers\WishListService;
use Illuminate\Support\Facades\URL;


/**
 *
 */
class ProductCategory extends BaseComponent {

    protected $composite        = true;
    private $additional         = false;
    private $direction          = true;
    private $product_single     = null;
    private $product_compact    = null;
    private $param              = null;
    private $limit              = 10;
    private $sort               = null;
    private $search             = '';
    private $filters            = [];
    private $last               = null;
    private $stock              = '';
    private $presales           = '';
    private $on_sale            = '';
    private $page               = 0;

    protected $css = ['ProductCategory/css/ProductCategory.css'];
    protected $js = ['ProductCategory/js/ProductCategory.js'];

    public function __construct(
        $product_single = null,
        $product_compact = null,
        $params = null,
        $filter = null,
        $additional = false,
        $banner = null,
        $full_url = null
    ) {
        $components = [];
        if ($product_single) {
            $components[] = $product_single;
        }

        if ($product_compact) {
            $components[] = $product_compact;
        }

        if ($banner) {
            $components[] = $banner;
        }

        parent::__construct($components);

        $this->product_single   = $product_single;
        $this->product_compact  = $product_compact;
        if (empty($additional)) {
            $this->additional       = false;
        } else {
            $this->additional       = $additional[0] === 'akcija';
        }
        $this->param            = $params;
        $this->banner           = $banner;
        $this->full_url         = $full_url;
        $this->fragmentUrl($filter);

        $this->js_config['category_id'] = $params;
    }

    public function renderHTML() {
        $limit = $this->limit;
        if ($this->last === null && $this->page > 0) {
            $limit = $limit * $this->page;
        }

        $products = ProductService::getProducts(
            $this->param,
            $this->direction,
            $this->filters,
            $this->sort,
            $limit,
            $this->search,
            $this->last,
            $this->additional,
            $this->stock,
            $this->presales,
            $this->on_sale
        );

        $args = [
            'category'          =>  CategoryService::getCategoryById($this->param),
            'products'          =>  $products,
            'product_single'    =>  $this->product_single,
            'product_compact'   =>  $this->product_compact,
            'sale'              =>  $this->additional ? 'sale' : 'notsale',
            'banner'            =>  $this->banner,
            'url'               =>  URL::current(),
        ];

        return view('ProductCategory/templates/ProductCategory', $args);
    }

    public function fetchData($params) {
        $url            = $params['url'];
        $append         = boolval($params['append']);
        $banners_count  = intval($params['banners_count']);
        $append_count   = intval($params['append_count']);
        $category       = $params['category'];

        if ($append === false) {
            BannerService::removeShownBanners();
        }
        $url_filter = [];
        if (is_array($url)) {
            foreach ($url as $filter_name => $filter_value) {
                $url[$filter_name] = explode(',', $filter_value);
                foreach ($url[$filter_name] as $key => $value) {
                    $new_value = str_replace('commastring', ', ', $value);
                    $url[$filter_name][$key] = $new_value;
                    if (is_numeric($value)) {
                        $url[$filter_name][$key] = intval($new_value);
                    }
                }
            }
            $url_filter = $url;
        }
        $category_id    = $params['category_id'];
        $last           = $params['last'];
        $last           = is_numeric($last) ? intval($last) : $last;
        $sale           = $params['sale'];

        $this->fragmentUrl($url_filter);

        $position  = BannerService::getPositionByName('Svaki peti u listi');
        $banner_after_products = 6;

        // dohvatim ukupan broj proizvoda odstampanih i to podelim sa brojem proizvoda
        // pre svakog banera. Dobijem koliko ukupno banera treba da ima, onda oduzmem odstampane vec.
        $nr_banners = floor($this->limit * ($append_count + 1) / $banner_after_products) - $banners_count;
        $banners = BannerService::getBannersByUrl(
            $position->id,
            $category,
            'product_category',
            $nr_banners,
            false
        );
        $limit = $this->limit;
        if ($last === '' && $this->page > 0) {
            $limit = $limit * $this->page;
        }

        return [
            'banners'   => $banners,
            'cart'      => ShopService::getUserCartCurrent(),
            'compare'   => ProductService::getComparingProducts(),
            'wishlist'  => WishListService::getWishListCurrent(),
            'products'  => ProductService::getProducts(
                $category_id,
                $this->direction,
                $this->filters,
                $this->sort,
                $limit,
                $this->search,
                $last,
                $sale,
                $this->stock,
                $this->presales,
                $this->on_sale
            ),
        ];
    }

    public function fragmentUrl($url) {
        if (!empty($url)) {
            foreach ($url as $key => $value) {
                if ($key !== '') {
                    if ($key === 'limit') {
                        // zbog paginacije dohvatam 1 vise
                        $this->limit = intval($value[0]) + 1;
                    } elseif ($key === 'sort') {
                        $sort = explode('_', $value[0]);
                        if ($sort[0] === 'price') {
                            $sort[0] = 'price_discount';
                        }

                        $this->sort = $sort[0];
                        $sort[1] === 'desc' ? $this->direction = false : $this->direction = true;
                    } elseif ($key === 'search') {
                        $this->search = $value[0];
                    } elseif ($key === 'stock') {
                        $this->stock = $value[0];
                    } elseif ($key === 'presales') {
                        $this->presales = $value[0];
                    } elseif ($key === 'on_sale') {
                        $this->on_sale = $value[0];
                    } elseif ($key === 'strana') {
                        $this->page = $value[0];
                    } else {
                        if (preg_match('/Min\:[0-9]+-Max\:[0-9]+/', $value[0])) {
                            $vals = explode('-', $value[0]);
                            $min  = explode(':', $vals[0])[1];
                            $max  = explode(':', $vals[1])[1];
                            $this->filters[$key]['min'] = $min;
                            $this->filters[$key]['max'] = $max;
                        } else {
                            $this->filters[$key] = $value;
                        }
                    }
                }
            }
        }
    }
}
