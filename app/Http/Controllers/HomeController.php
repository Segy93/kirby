<?php

namespace App\Http\Controllers;

use App\Components\ArticleList;
use App\Components\ArticlePage;
use App\Components\ArticleRecommended;
use App\Components\ArticleSingle;
use App\Components\AtomProductSingleCompact;
use App\Components\Banner;
use App\Components\Breadcrumbs;
use App\Components\Cart;
use App\Components\CategoryGrid;
use App\Components\CategoryList;
use App\Components\CheckoutPage;
use App\Components\CheckoutSuccess;
use App\Components\CheckoutTable;
use App\Components\Columns;
use App\Components\CommentSingle;
use App\Components\CommentsList;
use App\Components\ComparedProductPage;
use App\Components\ConfigurationList;
use App\Components\CookiesInfo;
use App\Components\FeaturedProducts;
use App\Components\Footer;
use App\Components\FooterBasicInformation;
use App\Components\FooterContact;
use App\Components\FooterInfo;
use App\Components\FooterItNews;
use App\Components\FooterServices;
use App\Components\FooterWorkTime;
use App\Components\Header;
use App\Components\HeaderCompanyInfo;
use App\Components\HeaderLogo;
use App\Components\HeaderSearchBar;
use App\Components\Login;
use App\Components\MainContainer;
use App\Components\MainHeader;
use App\Components\MainMenu;
use App\Components\MenuSliderBox;
use App\Components\OrderDetails;
use App\Components\OrderList;
use App\Components\PageNotFound;
use App\Components\ProductCategory;
use App\Components\ProductFilter;
use App\Components\ProductPage;
use App\Components\ProductRating;
use App\Components\ProductSingle;
use App\Components\RecommendedList;
use App\Components\SearchList;
use App\Components\SettingsFilters;
use App\Components\SocialList;
use App\Components\SocialShare;
use App\Components\StaticCategory;
use App\Components\UserMenuWidget;
use App\Components\UserProfile;
use App\Components\WishList;

use App\Exceptions\DatabaseException;
use App\Exceptions\ValidationException;

use App\Providers\AddressService;
use App\Providers\AdminService;
use App\Providers\BannerService;
use App\Providers\CommentService;
use App\Providers\ConfigService;
use App\Providers\ConfigurationService;
use App\Providers\ConfiguratorService;
use App\Providers\SearchService;
use App\Providers\SEOService;
use App\Providers\SessionService;
use App\Providers\ShopService;
use App\Providers\UserService;
use App\Providers\ValidationService;
use App\Providers\WishListService;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class HomeController extends BaseController {
    public function __construct(EntityManagerInterface $em) {
        BannerService::removeShownBanners();
        parent::__construct($em);
        if (UserService::isUserLoggedIn()) {
            try {
                if (UserService::isUserBanned()) {
                    UserService::logOutLocal();
                    return redirect()->route('login');
                } elseif (UserService::hasBanExpired()) {
                    UserService::removeExpiredBan();
                }

                UserService::updateVisitTime(UserService::getCurrentUserId());
            } catch (DatabaseException $e) {
                UserService::logOutLocal();
                return redirect()->route('login');
            }
        }
        UserService::setReturnUrl(URL::current());
    }

    public static function setSession($key, $value, $is_array = false) {
        $service = 'HomeController';
        return SessionService::setSessionForService($key, $value, $is_array, $service);
    }

    public function deleteSession($key) {
        $service = 'HomeController';

        return SessionService::deleteSession($key, $service);
    }

    protected function pageHeader():void {
        $header_logo                = new HeaderLogo();
        $user_menu                  = new UserMenuWidget();
        $search                     = new HeaderSearchBar();
        $company_info               = new HeaderCompanyInfo();
        $this->content['header'] [] = new Header($header_logo, $company_info, $user_menu, $search);
    }

    public function pageMenu(string $page):void {
        $expanded_menu = $page === 'index';
        $nr_slides = ConfigService::getBannerSliderMaxSlides();
        $slider    = new Banner('Slajder', null, '', $nr_slides);
        $main_menu = new MainMenu($expanded_menu);

        $this->content['navigation'] [] = new MenuSliderBox($main_menu, $slider, $expanded_menu);
    }

    public function p404($params = []) {
        $this->content['main'] [] = new MainContainer([
            [],
            [
                new PageNotFound(),
            ],
        ]);
    }


    public function indexPage($params = []) {
        $product_rating             = new ProductRating();
        $product_single__compact    = new AtomProductSingleCompact($product_rating);
        $sale_categories            = new CategoryGrid();
        $main_header                = new MainHeader();
        $components = [];
        $main_categories = [
            'Mobilni računari' => [],

            'Računari' => [
                'Elite PC',
                'Laptopovi',
                'Grafičke karte',
                'Hard diskovi',
                'Eksterni HDD',
            ],

            'Periferije i oprema' => [],

            'Potrošačka elektronika' => [],
        ];

        array_push($components, $main_header);
        array_push($components, $sale_categories);

        foreach ($main_categories as $name => $main_category) {
            $banner  = new Banner('Početna strana baner', null, 1);
            array_push($components, $banner);
            array_push($components, new FeaturedProducts($product_single__compact, $main_category, $name));
        }

        $this->content['main'] []   = new MainContainer([
            [
                new Banner('Pozadina'),
            ],
            $components,

        ]);
    }

    public function parentCategoryPage($params = [], $additional = []) {
        $this->content['main'] [] = new MainContainer([
            [],
            [
                new Breadcrumbs(),
                new CategoryGrid($params),
                // new ParentCategory($params),
            ],
        ]);
    }



    public function categoryPage($params = [], $additional = [], $url = [], $full_url = '') {
        $product_single     = new ProductSingle();
        $product_compact    = new AtomProductSingleCompact();
        $url_filter         = [];
        $url_raw            = $url;
        $banner_category    = new Banner('Svaki peti u listi', $full_url, 'product_category');
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
        $on_sale = strpos($full_url, 'akcija') !== false;

        $this->content['main'] [] = new MainContainer([
            [
                new Banner('Pozadina', $full_url, 'product_category'),
            ],
            [
                new Breadcrumbs(),
                new SettingsFilters(),
                new Columns(1, [
                    [
                        new ProductFilter($url_filter, $params, $on_sale)
                    ],
                    [
                        new ProductCategory(
                            $product_single,
                            $product_compact,
                            $params,
                            $url_filter,
                            $additional,
                            $banner_category,
                            $full_url
                        )
                    ],
                ])
            ],
        ]);
    }

    /**
     * Stranica staticne kategorije vezana seo tabelom
     *
     * @param int $params    Id kategorije
     * @return void
     */
    public function static_categoryPage(int $params): void {
        $this->content['main'] [] = new MainContainer([
            [],
            [
                new Breadcrumbs(),
                new StaticCategory($params, 'category'),
            ],
        ]);
    }

    /**
     * Stranica staticne strane vezana seo tabelom
     *
     * @param int $params    Id strane
     * @return void
     */
    public function staticPage(int $params): void {
        $this->content['main'] [] = new MainContainer([
            [],
            [
                new Breadcrumbs(),
                new StaticCategory($params, 'page'),
            ],
        ]);
    }

    public function searchPage($params) {
        $query = $params[0];
        $heading = 'Rezultati pretrage za "' . $query . '"';
        $product_single = new ProductSingle();
        $this->content['main'] [] = new MainContainer([
            [],
            [
                new Breadcrumbs(),
                new MainHeader($heading),
                new SearchList($product_single, $query),
            ],
        ]);
    }

    public function pageFooter():void {
        $basic_information  = new FooterBasicInformation();
        $services           = new FooterServices();
        $news               = new FooterItNews();
        $contact            = new FooterContact();
        $worktime           = new FooterWorkTime();
        $social_share_lg    = new SocialList();
        $info               = new FooterInfo();
        $info               = new FooterInfo();

        $this->content['footer'][] = new Footer(
            $basic_information,
            $services,
            $news,
            $contact,
            $worktime,
            $social_share_lg,
            $info
        );

        $this->content['footer'] [] = new CookiesInfo();
    }

    public function loginPage($params) {
        $form_state = $params['form_state'] ?? trim($_SERVER['REQUEST_URI'], '/');
        $error_code = $params['error_code'] ?? 0;
        $this->content['main'][] = new MainContainer([
            [

            ],
            [
                new Breadcrumbs(),
                new Login($form_state, $error_code),
            ]
        ]);
    }

    public function cartPage() {
        $this->content['main'] [] = new MainContainer([
            [],
            [
                new Breadcrumbs(),
                new Cart(),
            ],
        ]);
    }

    public function comparePage() {
        $this->content['main'][] = new MainContainer([
            [],
            [
                new Breadcrumbs(),
                new ComparedProductPage()
            ],
        ]);
    }

    public function productPage($params, $additional = [], $query = []) {
        $is_configurator = false;
        $configuration_name = ConfigurationService::$reserved_name;
        if (array_key_exists('konfigurator', $query)) {
            $is_configurator = true;
            $configuration_name = $query['konfigurator'];
        }
        $active_tab = '';
        $active_tab = $query['tab'] ?? '';
        $breadcrumbs =  new Breadcrumbs();
        $comment_single     = new CommentSingle();
        $comment_list       = new CommentsList($params, $comment_single, 'Product');
        $this->content['main'][] = new MainContainer([
            [
                // new Banner(),
            ],
            [
                new Breadcrumbs(),
                new ProductPage($params, $breadcrumbs, $comment_list, $active_tab, $is_configurator, $configuration_name),
            ],
        ]);
    }

    public function wishListPage($params) {
        $this->content['main'] [] = new MainContainer([
            [],
            [
                new Breadcrumbs(),
                new WishList(),
            ],
        ]);
    }

    public function profilePage($params) {
        $active_tab = array_key_exists('active_tab', $params) ? $params['active_tab'] : '';
        $activation = array_key_exists('activate', $params) ? $params['activate'] : false;

        $info = [];
        if (is_bool($activation) && $activation === true) {
            $info['activation'] = 'Uspešno ste aktivirali mail';
        } else {
            $info['error']      = $activation;
        }

        $cart       = new Cart();
        $wishlist   = new WishList();
        $order      = new OrderList();
        $configuration_list = new ConfigurationList();
        $this->content['main'] [] = new MainContainer([
            [],
            [
                new Breadcrumbs(),
                new UserProfile($cart, $wishlist, $order, $configuration_list, $info, $active_tab),
            ],
        ]);
    }

    public function orderUserSinglePage(int $order_id) {
        $this->content['main'] [] = new MainContainer([
            [],
            [
                new Breadcrumbs(),
                new OrderDetails($order_id),
            ],
        ]);
    }

    public function checkoutPage(bool $is_configuration = false, ?string $name = null) {
        $this->content['main'][] = new MainContainer([
            [],
            [
                new BreadCrumbs(),
                new CheckoutPage($is_configuration, $name),
            ]
        ]);
    }

    public function checkoutTablePage() {
        // $this->content['main'][] = new CheckoutTable();
        $this->content['main'][] = new MainContainer([
            [],
            [
                new BreadCrumbs(),
                new CheckoutTable(),
            ]
        ]);
    }


    public function checkoutSuccessPage() {
        // $this->content['main'][] = new CheckoutSuccess();
        $this->content['main'][] = new MainContainer([
            [],
            [
                new BreadCrumbs(),
                new CheckoutSuccess(),
            ]
        ]);
    }

    public function articleCategoryPage($params, $additional = []) {
        $seo_machine_name = 'articleCategory_' . $params;
        $social_share_xs = new SocialShare('xs');
        $article_single = new ArticleSingle($social_share_xs);
        $type = 'category';
        $date_info = urldecode($additional[0]);
        $data = empty($additional) ? [null, true] : explode('|', $date_info);
        //$content['main'][] = new Breadcrumbs('category_id', $params);
        $this->content['main'][] = new MainContainer([
            [],
            [
                new Breadcrumbs(),
                new Columns(0, [
                    [
                        new ArticleList($params, $type, $article_single, $data[0], $data[1]),
                    ], [
                        new CategoryList(),
                        new RecommendedList(),
                    ]
                ])
            ]
        ]);

        //$content['main']    []= new CategoryGrid();
    }

    // Dodao $params bunio se zbog toga.
    public function articlePage($params) {
        $seo_machine_name = 'article_' . $params;
        $social_share_lg        = new SocialShare('lg');
        $social_share_xs        = new SocialShare('xs');

        $recommended_articles   = new ArticleRecommended($params, $social_share_xs);



        $comment_single     = new CommentSingle();
        $comment_list       = new CommentsList($params, $comment_single, 'Article');

        $this->content['main'][] = new MainContainer([
            [],
            [
                new Breadcrumbs('article', $params),
                new Columns(0, [
                    [
                        new ArticlePage(
                            $params,
                            $recommended_articles,
                            $social_share_lg,
                            $social_share_xs,
                            $comment_list
                        ),
                    ],
                    [
                        new CategoryList(),
                        new RecommendedList(),

                    ]
                ])
            ]
        ]);
        //$content['main']    []= new CategoryGrid();
    }

    public function tagPage($params, $additional = []) {

        $seo_machine_name = 'tag_' . $params;
        $social_share_xs = new SocialShare('xs');
        $article_single = new ArticleSingle($social_share_xs);
        $type = 'tag';
        $date_info = urldecode($additional[0]);
        $data = empty($additional) ? [null, true] : explode('|', $date_info);
        //$content['main'][] = new Breadcrumbs('category_id', $params);

        $this->content['main'][] = new MainContainer([
            [],
            [
                new Breadcrumbs(),
                new Columns(0, [
                    [
                        new ArticleList($params, $type, $article_single, $data[0], $data[1]),
                    ], [
                        new CategoryList(),
                        new RecommendedList(),
                    ]
                ])
            ]
        ]);
    }


    public function authorPage($params, $additional = []) {
        $social_share_xs = new SocialShare('xs');
        $article_single = new ArticleSingle($social_share_xs);
        $type = 'author';
        $date_info = urldecode($additional[0]);
        $data = empty($additional) ? [null, true] : explode('|', $date_info);
        //$content['main'][] = new Breadcrumbs('category_id', $params);
        $this->content['main'][] = new MainContainer([
            [],
            [
                new Breadcrumbs(),
                new Columns(0, [
                    [
                        new ArticleList($params, $type, $article_single, $data[0], $data[1]),
                    ], [
                        new CategoryList(),
                        new RecommendedList(),
                    ]
                ])
            ]
        ]) ;
    }











    public function index(Request $request) {
        return $this->getView(
            'index',
            [$request->all()]
        );
    }

    public function category(Request $request) {

        return $this->getView(
            'category',
            [],
            [],
            [$request->all()]
        );
    }

    public function wishlist($user_id = null) {
        $current_user = UserService::getCurrentUserId();
        $is_admin     = AdminService::isAdminLoggedIn();
        $show_for = $is_admin || $user_id === $current_user ? $user_id : $current_user;
        return $this->getView(
            'wishList',
            $show_for
        );
    }

    public function author($username = null, $additional = null) {
        return $this->getView(
            'author',
            $username,
            $additional
        );
    }

    public function removeWish(Request $request) {
        $id = $request->input('id');
        WishListService::deleteFromList($id);
        return redirect()->route('cart');
    }

    public function article() {
        return $this->getView(
            'article'
        );
    }

    public function articles() {
        return $this->getView(
            'articles'
        );
    }

    public function cart() {
        return $this->getView(
            'cart'
        );
    }

    public function sitemap() {
        SEOService::createSitemap();
    }

    public function parentCategory(Request $request) {
        $path = urldecode($request->path());
        return $this->getView('parentCategory', $path);
    }

    public function checkoutAddAddress(Request $request) {
        if (UserService::isUserLoggedIn()) {
            if ($request->input('add_address')) {
                $user_id                = UserService::getCurrentUserId();
                $name                   = $request->input('name');
                $surname                = $request->input('surname');
                $company                = $request->input('company');
                $address                = $request->input('address');
                $post_code              = $request->input('post_code');
                $phone                  = $request->input('phone');
                $city                   = $request->input('city');

                $updateAddress = AddressService::createAddressUser(
                    $user_id,
                    $city,
                    $name,
                    $surname,
                    $address,
                    $post_code,
                    $phone,
                    false,
                    false,
                    $company
                );
            }

            return redirect()->route('checkout');
        } else {
            return redirect()->route('login');
        }
    }



    public function checkout() {
        $cart       = ShopService::getUserCartCurrent();
        $is_configurator = false;
        $name = null;
        if (!empty($cart)) {
            return $this->getView(
                'checkout',
                $is_configurator,
                $name
            );
        } else {
            return redirect()->route('cart');
        }
    }

    public function checkoutConfigurator(?string $name = null) {
        if ($name !== null) {
            $name = urldecode($name);
        }
        $is_configuration = true;
        $configuration = ConfigurationService::getConfigurationArray($name);
        if (!empty($configuration['products'])) {
            return $this->getView(
                'checkout',
                $is_configuration,
                $name
            );
        } else {
            return redirect()->route('configurator');
        }
    }

    public function checkoutSuccess() {
        $cart       = ShopService::getUserCartCurrent();
        if (!empty($cart)) {
            return $this->getView(
                'checkoutSuccess'
            );
        } else {
            return redirect()->route('checkoutTable');
        }
    }

    public function checkoutTable() {
        if (UserService::isUserLoggedIn()) {
            $user_id    = UserService::getCurrentUserId();
            $order      = ShopService::getOrderByUserIdStatus($user_id);
            if ($order !== null) {
                return $this->getView(
                    'checkoutTable'
                );
            }
        } else {
            return redirect()->route('login');
        }
    }

    public function checkoutPost(Request $request) {
        $user = UserService::getCurrentUser();
        $user_id = $user !== null ? $user->id : null;
        $username = $user !== null ? $user->username : null;
        $this->deleteSession('checkoutErrors');
        $type               = $request->input('type');
        $configuration_id   = $request->input('configuration_id') !== ''
            ? intval($request->input('configuration_id'))
            : null
        ;
        $errors         = [];
        $name           = $request->input('user_data_name');
        $surname        = $request->input('user_data_surname');
        $phone_nr       = $request->input('user_data_phone');
        $terms_of_use   = $request->input('terms_ofuse') === 'on';
        $captcha        = $request->input('g-recaptcha-response');
        $is_logged_in   = UserService::isUserLoggedIn();

        $cart               = ShopService::getUserCartCurrent();
        $configuration      = ConfigurationService::getConfigurationByUserIdConfigurationId($user_id, $configuration_id);

        if ($type === 'cart' && empty($cart)) {
            return redirect()->route('cart');
        } else if ($type === 'configuration' && empty($configuration)) {
            if ($configuration_id === null) {
                return redirect()->route('configurator');
            } else {
                $configuration_name = ConfigurationService::getConfigurationById($configuration_id)->name;
                return redirect()->route('configurator', [
                    'username'  => urlencode($username),
                    'name'      => urlencode($configuration_name)
                ]);
            }
        }

        if (!$terms_of_use) {
            array_push($errors, 'Morate prihvatiti pravila korišćenja');
        }

        if ($is_logged_in === false) {
            if (ValidationService::validateRecaptcha($captcha) === true) {
                if ($request->has('temporary_email')) {
                    $email = $request->input('temporary_email');
                    $is_email_taken = UserService::isLocalEmailTaken($email);
                    $is_username_taken = UserService::isLocalUsernameTaken(explode('@', $email)[0]);

                    if ($is_email_taken || $is_username_taken) {
                        array_push($errors, 'Korisnik sa tim email-om ili username-om već postoji. Molimo prijavite se');
                    } else {
                        UserService::createTemporaryUser($email);
                    }
                } else {
                    array_push($errors, 'Morate uneti email ako niste registrovan korisnik');
                }
            } else {
                array_push($errors, 'Morate da uradite recaptch-u');
            }
        }

        $user_id                = UserService::getCurrentUserId();
        $shipping_address_id    = $request->input('shipping_address');
        $billing_address_id     = $request->input('billing_address');
        // Ako je ista adresa dodje do baga kod kreiranja billing adrese posto
        // vise ne postoji u sesiji pa ovako nemam duplo kreiranje iste adrese
        $same_address       = $shipping_address_id === $billing_address_id;
        if ($shipping_address_id < 0) {
            // $shipping_address je id iz sesije i kada se kreira adresa postaje id te adrese
            $shipping_address_id = AddressService::createAddressFromSession($shipping_address_id);
        }
        if ($billing_address_id < 0) {
            if ($same_address) {
                $billing_address_id = $shipping_address_id;
            } else {
                // $billing_address je id iz sesije i kada se kreira adresa postaje id te adrese
                $billing_address_id    = AddressService::createAddressFromSession($billing_address_id);
            }
        }
        SessionService::deleteSession('addresses', 'address_service');

        $preferred_shipping  = AddressService::getUserPreferedAddress('shipping');
        $preferred_billing   = AddressService::getUserPreferedAddress('billing');

        $shipping_address = AddressService::getAddressById($shipping_address_id);
        if ($preferred_shipping === null && $shipping_address->address_type !== 'shop') {
            AddressService::updateAddress($shipping_address->id, ['preferred_address_delivery' => true]);
        }

        $billing_address = AddressService::getAddressById($billing_address_id);
        if ($preferred_billing === null && $billing_address->address_type !== 'shop') {
            AddressService::updateAddress($billing_address->id, ['preferred_address_billing' => true]);
        }

        $coupon                 = $request->input('coupon');
        $payment                = intval($request->input('payment_type'));
        $note                   = $request->input('note');
        $online_token           = null;

        if ($request->input('arrival_date') === '') {
            array_push($errors, 'Morate uneti datum dostave');
            $date_delivery = '';
        } elseif (\DateTime::createFromFormat('Y-m-d', $request->input('arrival_date')) === false) {
            array_push($errors, 'Datum nije odgovarajućeg formata');
            $date_delivery = '';
        } else {
            $today  = new \DateTime();
            $today  = $today->modify(date('Y-m-d H:i:s'));
            $input  = new \DateTime();
            $input->modify($request->input('arrival_date'));

            if ($input < $today) {
                array_push($errors, 'Datum dostave mora biti nakon današnjeg dana');
                $date_delivery = '';
            } else {
                $date_delivery = $input;
            }
        }

        if ($name === '') {
            array_push($errors, 'Morate uneti ime');
        }

        if ($surname === '') {
            array_push($errors, 'Morate uneti prezime');
        }

        if ($phone_nr === '') {
            array_push($errors, 'Morate uneti telefon');
        }

        if ($user_id !== false) {
            $update_user = UserService::updateLocalUser($user_id, [
                'name'              => $name,
                'surname'           => $surname,
                'phone_nr'          => $phone_nr,
            ]);
        } else {
            $update_user = false;
        }

        if ($date_delivery !== ''
            && $user_id !== false
            && $name !== ''
            && $surname !== ''
            && $phone_nr !== ''
        ) {
            $order = ShopService::placeOrder(
                $user_id,
                $payment,
                $shipping_address,
                $billing_address,
                $online_token,
                $date_delivery,
                $note,
                $type,
                $configuration_id
            );
        } else {
            $order = false;
        }

        if ($update_user && $order && empty($errors)) {
            return redirect()->route('checkoutTable');
        } else {
            self::setSession('checkoutErrors', $errors);
            return redirect()->route('checkout');
        }
    }

    public function checkoutConfirm(Request $request) {
        $user_id    = UserService::getCurrentUserId();
        $user       = UserService::getUserById($user_id);
        $order      = ShopService::getOrderByUserIdStatus($user_id);
        $input      = $request->input('confirm_submit');
        if ($input === 'back') {
            $cancel = ShopService::cancelUnconfirmedOrder($user_id);
            return redirect()->route('checkout');
        }

        $confirm = ShopService::createOrderUpdate(
            $order->id,
            null,
            'potvrđeno',
            null,
            'Potvrđena narudžbina',
            false
        );

        if ($order->delivery_address->address_type !== 'shop') {
            ShopService::calculateOrderShippingFee($order->id, $order->total_price);
        }

        if ($confirm) {
            return redirect()->route('OrderUser', ['username' => $user->username, 'id' => $order->id]);
        } else {
            return redirect()->route('checkoutTable');
        }
    }

    public function search(Request $request) {
        $query   = $request->input('query');
        return $this->getView('search', [$query]);
    }

    public function compare() {
        return $this->getView(
            'compare'
        );
    }


    public function login() {
        return $this->getView('login', ['form_state' => 'login']);
    }

    public function register() {
        return $this->getView('login', ['form_state' => 'register']);
    }

    public function forgotPassword() {
        $error_code = $_SESSION['home_controller']['reset_password_error'] ?? 0;

        return $this->getView('login', [
            'form_state' => 'forgot_password',
            'error_code' => $error_code,
        ]);
    }

    // Lista narudzbina na profilu

    public function profileOrders($username) {
        if (UserService::isUserLoggedIn()) {
            $params = [
                'active_tab' => 'Narudžbine',
            ];

            return $this->getView(
                'profile',
                $params
            );
        } else {
            return redirect()->route('login');
        }
    }

    public function profile($username, $token = null) {
        $activate = null;
        if ($token !== null) {
            $activate = UserService::confirmEmail($token);
        }

        $params = [];
        $params['activate'] = $activate;
        if (UserService::isUserLoggedIn()) {
            return $this->getView(
                'profile',
                $params
            );
        } else {
            return redirect()->route('login');
        }
    }

    // Metoda za dohvatanje detalja narudzbine na osnovu id iz url adrese (drugi argument),
    // prvi argument trenutno nije potreban
    public function orderUserSingle(string $username, int $order_id) {
        if (UserService::isUserLoggedIn()) {
            return $this->getView(
                'orderUserSingle',
                $order_id
            );
        } else {
            return redirect()->route('login');
        }
    }

    public function addToCart(Request $request) {
        // +1 jer je trenutno stanje 0 i povecavamo u odnosu na to
        $quantity   = intval($request->input('quantity')) + 1;
        $product_id = intval($request->input('product_id'));
        $user_id    = UserService::getCurrentUserId();
        ShopService::changeCart($product_id, $quantity, $user_id);
        return redirect()->route('cart');
    }

    public function removeCart(Request $request) {
        $id = intval($request->input('id'));
        ShopService::deleteCart($id);
        return redirect()->route('cart');
    }

    public function changeCart(Request $request) {
        $this->deleteSession('changeCartErrors');

        try {
            $quantity   = intval($request->input('quantity'));
            $product_id = intval($request->input('product_id'));
            $user_id    = UserService::getCurrentUserId();
            ShopService::changeCart($product_id, $quantity, $user_id);
            return redirect()->route('cart');
        } catch (\Exception $e) {
            $error = $e->getMessage();
            self::setSession('changeCartErrors', $error);
            return redirect()->route('cart');
        }
    }









    public function registerUser(Request $request) {
        $captcha    = $request->input('g-recaptcha-response');
        $username   = $request->input('username');
        $email      = $request->input('email');
        $password   = $request->input('password');
        // $username   = strstr($email, '@', true);
        $accept     = $request->input('accept');

        $error_code = 0;

        if ($accept === 'on') {
            try {
                if (ValidationService::validateRecaptcha($captcha) === true) {
                    $response = UserService::signUpLocal($username, $email, $password);

                    if (is_numeric($response)) {
                        $error_code = $response;
                    } else {
                        $res    = UserService::logInLocal($email, $password);
                        $user   = UserService::getCurrentUser();
                        return redirect('korisnik/' . $user->username);
                    }
                } else {
                    return redirect()->route('register');
                }
            } catch (ValidationException $exception) {
                $error_code = $exception->getCode();
            } catch (UniqueConstraintViolationException $exception) {
                $error_code = 1062;
            }
        } else {
            $error_code = 5;
        }

        return $this->getView('login', [
            'form_state' => $error_code === 0 ? 'login' : 'register',
            'error_code' => $error_code,
        ]);
    }

    public function loginUser(Request $request) {
        $email      = $request->input('email');
        $password   = $request->input('password');
        $remember   = $request->input('remember') === 'on';

        try {
            UserService::logInLocal($email, $password, $remember);
            $_SESSION['home_controller']['failed_attempts_user'] = 0;
            $user   = UserService::getCurrentUser();
            $return_url = UserService::getReturnUrl();
            return !empty($return_url) ? redirect($return_url) : redirect('korisnik/' . $user->username);
            //return redirect('korisnik/'.$user->username);
        } catch (\Exception $e) {
            $_SESSION['home_controller']['failed_attempts_user'] = array_key_exists('failed_attempts_user', $_SESSION)
                ? $_SESSION['home_controller']['failed_attempts_user'] + 1
                : 1
            ;

            sleep($_SESSION['home_controller']['failed_attempts_user'] * (rand(1, 3) * 0.1));

            return $this->getView('login', [
                'form_state' => 'login',
                'error_code' => $e->getCode(),
            ]);
        }
    }

    public function logout() {
        UserService::logOutLocal();
        return redirect()->route('index');
    }

    public function emailActivation($token) {
        $response = UserService::confirmEmail($token);
        $user     = UserService::getCurrentUser();
        return $this->getView('profile', [
            'activate' => true,
        ]);
        //return redirect('korisnik/'.$user->username);
    }

    public function resetPasswordCheckEmail(Request $request) {
        $captcha = $request->input('g-recaptcha-response');
        $email = $request->input('email');

        try {
            if (ValidationService::validateRecaptcha($captcha) === true) {
                UserService::resetPassword($email);
                return $this->getView('login', [
                    'form_state' => 'forgot_success',
                ]);
            } else {
                return redirect()->route('forgot_password');
            }

            unset($_SESSION['home_controller']['reset_password_error']);
        } catch (ValidationException $exception) {
            $_SESSION['home_controller']['reset_password_error'] = $exception->getCode();
            return redirect()->route('forgot_password');
        }
    }

    public function resetPasswordEnterNew($token) {
        if (UserService::isPasswordResetTokenValid($token)) {
            $_SESSION['home_controller']['password_reset_token'] = $token;
            return $this->getView('login', [
                'form_state' => 'reset',
            ]);
        } else {
            return redirect()->route('forgot_password');
        }
    }

    public function resetPassword(Request $request) {
        if (array_key_exists('home_controller', $_SESSION)
            && array_key_exists('password_reset_token', $_SESSION['home_controller'])
        ) {
            try {
                $token = $_SESSION['home_controller']['password_reset_token'];
                $new_password = $request->input('password');
                $repeat_password = $request->input('password_confirm');

                UserService::confirmResetPassword($token, $new_password, $repeat_password);
                return redirect('prijava');
            } catch (ValidationException $exception) {
                return $this->getView('login', [
                    'form_state' => 'reset',
                    'error_code' => $exception->getCode(),
                ]);
            }
        } else {
            return redirect()->route('forgot_password');
        }
    }

    public function cookiesAccepted() {
        if (UserService::isUserLoggedIn()) {
            $user_id = UserService::getCurrentUserId();
            UserService::updateLocalUser($user_id, ['cookies_accepted' => true]);
        } else {
            SessionService::setSessionForService('cookies_accepted', true, false, 'user_service');
        }

        $return_url = UserService::getReturnUrl();
        return !empty($return_url) ? redirect($return_url) : redirect('/');
    }

    public function pageNotFound() {
        return response($this->getView('404'), 404);
    }

    public function newComment(Request $request) {

        $return_url = UserService::getReturnUrl();
        $message        = $request->input('text');
        $parent_id      = null;
        $type           = $request->input('type');
        $node_id        = $request->input('node_id');
        if ($request->has('parent_id')) {
            $parent_id           = $request->input('parent_id');
        }

        $user_id = UserService::getCurrentUserId();

        $comment = $type === 'Product' ?
        CommentService::createProductComment($user_id, $node_id, $message, $parent_id) :
        CommentService::createArticleComment($user_id, $node_id, $message, $parent_id) ;
        return !empty($return_url) ? redirect($return_url) : redirect('/');
    }






    public function dynamicPage($seo_url_raw, $additional = null, Request $request = null) {
        try {
            $seo_url_formatted = $additional === null
                ? $seo_url_raw
                : $seo_url_raw . '/' . $additional
            ;

            $seo = SEOService::getSEOByURL($seo_url_formatted);
            $seo = empty($seo) ? SEOService::getSEOByURL($seo_url_formatted . '/') : $seo;

            $url = $request->query->all();
            if (empty($seo)) {
                throw new \Exception('Stranica ne postoji', 404);
            }
            $info = explode('_', $seo->machine_name);
            if (count($info) === 2) {
                $type = $info[0];
                $params = intval($info[1]);
            } elseif (count($info) === 3) {
                $type = $info[0] . '_' . $info[1];
                $params = [];
                $params = intval($info[2]);
            } elseif (count($info) === 4) {
                $type = $info[0] . '_' . $info[2];
                $params = [];
                $params[$info[0] . '_id'] = $info[1];
                $params[$info[2] . '_id'] = $info[3];
            }

            $full_url = urldecode($request->fullUrl());
            if (!empty($additional)) {
                $additional = explode('/', $additional);
            }
            return $this->getView($type, $params, $additional, $url, $full_url);
        } catch (\Exception $e) {
            return redirect()->route('notFound');
        }
    }
}
