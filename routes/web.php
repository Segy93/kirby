<?php

/*
|--------------------------------------------------------------------------
| routerlication Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an routerlication.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// $router->get('/', function () use ($router) {
//    return $router->version();
// });

use Illuminate\Support\Facades\Log;

use App\Providers\UserService;

if (session_status() === PHP_SESSION_NONE) {
    $cookie_name = UserService::getCookieName();
    try {
        session_start([
            'name' => $cookie_name,
        ]);

        if (array_key_exists('token', $_SESSION) === false) {
            $_SESSION['token'] = bin2hex(random_bytes(32));
        }
    } catch (\Exception $e) {
        // setcookie($cookie_name, session_id(), time() + 3600, '', '', true, true, 'samesite=strict');
        Log::error($e->getMessage());
    }
}

date_default_timezone_set('UTC');

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 'On');

$router->get( // Početna strana
    '/',
    [
        'as'    => 'index',
        'uses'  => 'HomeController@index',
    ]
);

$router->get( // Početna strana
    'index',
    [
        'uses'  => 'HomeController@index',
        'as'    => 'index'
    ]
);










$router->get(
    'it-svet',
    [
        'uses' => 'ListingController@itSvet',
    ]
);










$router->get( // Staticka strana sa boksovima za kategorije
    'mobilni-ra%C4%8Dunari',
    [
        'uses'  => 'HomeController@parentCategory',
    ]
);

$router->get( // Staticka strana sa boksovima za kategorije
    'ra%C4%8Dunari',
    [
        'uses'  => 'HomeController@parentCategory',
    ]
);

$router->get( // Staticka strana sa boksovima za kategorije
    'periferije-i-oprema',
    [
        'uses'  => 'HomeController@parentCategory',
    ]
);

$router->get( // Staticka strana sa boksovima za kategorije
    'potro%C5%A1a%C4%8Dka-elektronika',
    [
        'uses'  => 'HomeController@parentCategory',
    ]
);










$router->get( // Kategorija, lista proizvoda sa filtriranjem
    'kategorija',
    [
        'uses' => 'HomeController@category',
    ]
);

$router->get( // Detalji proizvoda
    'artikal',
    [
        'uses' => 'HomeController@product',
    ]
);

// $router->get( //galerija proizvoda
//     'galerija',
//     [
//         'uses' => 'HomeController@product/galerija',
//     ]
// );

$router->get( // Korisnicka lista zelja
    'lista-zelja[/{id}]',
    [
        'uses' => 'HomeController@wishlist',
    ]
);

$router->get( // Korisnicka lista zelja
    'konfigurator[/{username}/{name}]',
    [
        'as'    => 'configurator',
        'uses'  => 'ConfiguratorController@newConfigurator',
    ]
);

$router->get( // Korisnicka lista zelja
    'lista-konfiguracija',
    [
        'as'    => 'configurationList',
        'uses'  => 'ConfiguratorController@configurationList',
    ]
);

$router->get( // Korisnicka lista zelja
    'konfigurator/dodaj/{name}/{category_url}',
    [
        'as'    => 'configuratorAdd',
        'uses'  => 'ConfiguratorController@configuratorProductList',
    ]
);

$router->post(
    'konfigurator-kreiraj',
    [
        'as'    => 'configuratorCreate',
        'uses'  => 'ConfiguratorController@configuratorCreate',
    ]
);

$router->post(
    'konfigurator-izmeni',
    [
        'as'    => 'configuratorEdit',
        'uses'  => 'ConfiguratorController@configuratorEdit',
    ]
);

$router->post(
    'konfigurator-brisanje-proizvoda',
    [
        'as'    => 'configuratorItemDelete',
        'uses'  => 'ConfiguratorController@configuratorItemDelete',
    ]
);

$router->post(
    'konfiguracija-brisanje',
    [
        'as'    => 'configurationDelete',
        'uses'  => 'ConfiguratorController@configurationDelete',
    ]
);


$router->post( // POST za dodavanje proizvoda u korpu
    'configurator_add',
    [
        'uses' => 'ConfiguratorController@addToConfigurator',
    ]
);

$router->get( // Narudzbine
    'korisnik/{username}/narudzbine',
    [
        'uses' => 'HomeController@profileOrders',
        'as'   => 'profileOrders'
    ]
);

$router->get( // Profil
    'korisnik/{username}[/{token}]',
    [
        'uses' => 'HomeController@profile',
        'as'   => 'profile'
    ]
);

$router->get(
    'korisnik/{username}/narudzbine/{id}',
    [
        'as'    => 'OrderUser',
        'uses'  => 'HomeController@orderUserSingle',
    ]
);

$router->get(
    'autori/{username}[/{additional}]',
    [
        'as'    => 'author',
        'uses'  => 'HomeController@author',
    ]
);

$router->get( // Poredjenje proizvoda
    'uporedi-proizvode',
    [
        'uses' => 'HomeController@compare',
    ]
);


$router->get(
    'sitemap',
    [
        'uses' => 'HomeController@sitemap',
    ]
);

$router->get(
    '404',
    [
        'uses'  => 'HomeController@pageNotFound',
        'as'    => 'notFound',
    ]
);










$router->get( // Prijava
    'prijava',
    [
        'as'   => 'login',
        'uses' => 'HomeController@login',
    ]
);

$router->post( // POST Prijava
    'prijava',
    [
        'as'    => 'login-post',
        'uses'  => 'HomeController@loginUser',
    ]
);

$router->get( // Odjava
    'logout',
    [
        'uses'  => 'HomeController@logout',
    ]
);

$router->get( // Registracija
    'registracija',
    [
        'as'    => 'register',
        'uses'  => 'HomeController@register',
    ]
);

$router->get(
    'zaboravljena-lozinka',
    [
        'as'    => 'forgot_password',
        'uses'  => 'HomeController@forgotPassword',
    ]
);

$router->post( // POST za unet email na koji treba poslati link zaz reset lozzinke
    'zaboravljena-lozinka',
    [
        'uses'  => 'HomeController@resetPasswordCheckEmail',
    ]
);

$router->get( // Aktivacija naloga
    'aktivacija/{token}',
    [
        'as'    => 'Aktivacija',
        'uses'  => 'HomeController@emailActivation',
    ]
);

$router->post( // POST za registraciju korisnika
    'registracija',
    [
        'as'    => 'register-post',
        'uses'  => 'HomeController@registerUser',
    ]
);

$router->get( // korisnik je otvorio link za reset lozinke u mejlu i dosao ovde
    'reset-lozinke/{token}',
    [
        'as'    => 'reset-password-enter-new',
        'uses'  => 'HomeController@resetPasswordEnterNew',
    ]
);

$router->post(
    'reset-lozinke',
    [
        'as'    => 'reset-password-post',
        'uses'  => 'HomeController@resetPassword',
    ]
);










$router->post( // POST za dodavanje proizvoda u korpu
    'cart_add',
    [
        'uses' => 'HomeController@addToCart',
    ]
);

$router->post( // POST za sklanjanje proizvoda iz korpe
    'removeCart',
    [
        'uses' => 'HomeController@removeCart',
    ]
);

$router->post(
    'changeCart',
    [
        'uses' => 'HomeController@changeCart',
    ]
);

$router->get( // Korpa
    'korpa',
    [
        'uses' => 'HomeController@cart',
        'as'   => 'cart',
    ]
);

$router->get( // Kasa
    'kasa',
    [
        'uses' => 'HomeController@checkout',
        'as'   => 'checkout'
    ]
);

$router->get( // Kasa
    'kasa-konfigurator[/{name}]',
    [
        'uses' => 'HomeController@checkoutConfigurator',
        'as'   => 'checkoutConfigurator'
    ]
);

$router->get( // Sumirani proizvodi u korpi
    'narudzbenica',
    [
        'uses' => 'HomeController@checkoutTable',
        'as'   => 'checkoutTable'
    ]
);

$router->get(
    'checkoutSuccess',
    [
        'uses' => 'HomeController@checkoutSuccess',
        'as'   => 'checkoutSuccess'
    ]
);

$router->post( // POST za dodavanje adrese prilikom kupovine
    'checkout/addAddress',
    [
        'uses' => 'HomeController@checkoutAddAddress',
    ]
);

$router->post( // POST za slanje sa kase
    'checkoutPost',
    [
        'uses' => 'HomeController@checkoutPost',
    ]
);


$router->get( // Strana pretrage
    'pretraga',
    [
        'uses' => 'HomeController@search',
    ]
);

$router->post( // POST za poslednji korak, potvrdu porudzbine
    'checkoutConfirm',
    [
        'uses' => 'HomeController@checkoutConfirm',
    ]
);

$router->post(
    'cookiesAccepted',
    [
        'uses' => 'HomeController@cookiesAccepted',
    ]
);



$router->post(
    'comment_post_new',
    [
        'uses' => 'HomeController@newComment',
    ]
);





// Rute za admina





$router->get( // Pocetna strana
    'admin',
    [
        'as'    => 'Admin',
        'uses'  => 'AdminController@administration',
    ]
);

$router->get( // Prijava
    'admin/login',
    [
        'as'    => 'LogIn',
        'uses'  => 'AdminController@login',
    ]
);

$router->post( // Provera sifre
    'admin/checkpassword',
    [
        'uses'  => 'AdminController@checkpassword',
    ]
);

$router->group(['middleware' => 'auth'], function () use ($router) {

    $router->get( // Odjava trenutnog admina
        'admin/odjava',
        [
            'as'    => 'LogOut',
            'uses'  => 'AdminController@logout',
        ]
    );

    $router->get( // Strana za prikaz dozvola
        'admin/dozvole',
        [
            'as'    => 'Permissions',
            'uses'  => 'AdminController@permissions',
        ]
    );

    $router->get( // Strana za pravljenje admina i njihova lista
        'admin/administratori',
        [
            'as'    => 'Administrators',
            'uses'  => 'AdminController@administrators',
        ]
    );

    $router->get( // Strana za pravljenje korisnika i njihova lista
        'admin/korisnici',
        [
            'as'    => 'Users',
            'uses'  => 'AdminController@users',
        ]
    );

    $router->get( // Strana za pravljenje korisničkih adresa i njihova lista
        'admin/korisnici/adrese/{userId}',
        [
            'as'    => 'Users',
            'uses'  => 'AdminController@usersAddresses',
        ]
    );

    $router->get( // Strana liste narudćbina
        'admin/narudzbine',
        [
            'as'    => 'Orders',
            'uses'  => 'AdminController@orders',
        ]
    );

    $router->get( // Strana za pojedinačnu narudžbinu
        'admin/narudzbina/{id}',
        [
            'as'    => 'Order',
            'uses'  => 'AdminController@order',
        ]
    );

    $router->get( // Strana za narudžbinicu
        'admin/narudzbenica/{id}',
        [
            'as'    => 'CheckoutTable',
            'uses'  => 'AdminController@checkoutTable',
        ]
    );

    $router->get( // Strana za banere
        'admin/baneri',
        [
            'as'    => 'Banners',
            'uses'  => 'AdminController@banner',
        ]
    );

    $router->get( // Strana za komentara
        'admin/komentari',
        [
            'as'    => 'Comments',
            'uses'  => 'AdminController@comment',
        ]
    );

    $router->get( // Strana za pravljenje članaka i njihova lista
        'admin/clanci',
        [
            'as'    => 'Articles',
            'uses'  => 'AdminController@articles',
        ]
    );

    $router->get( // Strana za izmenu članka
        'admin/clanci/izmena/{id}',
        [
            'as'    => 'Articles',
            'uses'  => 'AdminController@articlesEdit',
        ]
    );

    $router->get( // Strana za pravljenje tagova i njihova lista
        'admin/tagovi',
        [
            'as'    => 'Tags',
            'uses'  => 'AdminController@tags',
        ]
    );

    $router->get(//Strana za pravljenje kategorija i njihova lista
        'admin/kategorije',
        [
            'as' => 'Categories',
            'uses' => 'AdminController@categories',
        ]
    );

    $router->get(//Strana za pravljenje statičnih kategorija i njihova lista
        'admin/staticne/kategorije',
        [
            'as' => 'StaticCategories',
            'uses' => 'AdminController@staticCategories',
        ]
    );

    $router->get( //Strana za pravljenje statičnih strana i njihova lista
        'admin/staticne/strane',
        [
            'as' => 'StaticPages',
            'uses' => 'AdminController@staticPages',
        ]
    );

    $router->get( //Strana za pravljenje kategorija i njihova lista
        'admin/staticne/strana[/{page_id}]',
        [
            'as' => 'StaticPages',
            'uses' => 'AdminController@staticPage',
        ]
    );










    $router->get( // Strana za testiranje
        '/test',
        [
            'uses'  =>  'TestController@index',
        ]
    );










    $router->get(
        '/database',
        [
            'uses'  =>  'DataController@index',
        ]
    );

    $router->get(
        '/db_import/product[/{artid}]',
        [
            'uses'  =>  'DataController@productImport',
        ]
    );

    $router->get(
        '/db_import[/{category_name}]',
        [
            'uses'  =>  'DataController@productsImport',
        ]
    );

    $router->get(
        '/db_update/product[/{artid}]',
        [
            'uses'  =>  'DataController@productUpdate',
        ]
    );

    $router->get(
        '/db_update[/{category_name}]',
        [
            'uses'  =>  'DataController@productcUpdate',
        ]
    );
});










$router->post(
    'ajax/data',
    [
        'uses' => 'AjaxController@handleRequestRegular',
    ]
);

$router->post(
    'ajax/data_raw',
    [
        'uses' => 'AjaxController@handleRequestRaw',
    ]
);










$router->get(
    '{seo_url_raw}[/{additional:[A-Za-z0-9\/\-\.\(\)\+\%\|\:]+}]',
    [
        'as'    =>  'Page',
        'uses'  =>  'HomeController@dynamicPage',
    ]
);
