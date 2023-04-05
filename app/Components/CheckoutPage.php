<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\AddressService;
use App\Providers\BaseService;
use App\Providers\ConfigurationService;
use App\Providers\SessionService;
use App\Providers\ShopService;
use App\Providers\UserService;


/**
 * Strana kase
 */
class CheckoutPage extends BaseComponent {

    protected $css        = ['CheckoutPage/css/CheckoutPage.css'];
    protected $js         = ['CheckoutPage/js/CheckoutPage.js'];

    protected $is_configuration = false;
    protected $configuration_name = null;

    /**
     * Konstruktor
     *
     * @param   bool   $is_configuration    Da li je konfiguracija
     * @param   int    $configuration_name  Naziv konfiguracije
     */
    public function __construct(bool $is_configuration = false, ?string $configuration_name = null) {
        $this->is_configuration = $is_configuration;
        $this->configuration_name = $configuration_name;
    }

    public function renderHTML() {
        $user =  UserService::getCurrentUser();
        $user_id = UserService::getCurrentUserId();
        $configuration_id = $this->configuration_name !== null
            ? ConfigurationService::getConfigurationIdByName($this->configuration_name)
            : null
        ;
        $args = [
            'cart'                  => $this->is_configuration
                ? ConfigurationService::getConfigurationByUserIdConfigurationId($user_id, $configuration_id)
                : ShopService::getUserCartByUserId($user_id),
            'csrf_field'            => SessionService::getCsrfField(),
            'errors'                => SessionService::getSessionValueForService('checkoutErrors', 'HomeController'),
            'payment'               => ShopService::getPaymentMethods(),
            'shops'                 => ShopService::getShops(),
            'total_price_retail'    => $this->getTotalPriceRetail($this->is_configuration, $configuration_id),
            'total_price_discount'  => $this->getTotalPriceDiscount($this->is_configuration, $configuration_id),
            'shipping_fee'          => $this->calculateShippingFee($this->is_configuration, $configuration_id),
            'user'                  => $user ? $user : null,
            'is_configuration'      => $this->is_configuration,
            'configuration_id'      => $configuration_id,
            'site_key'              => config(php_uname('n') . '.GOOGLE_SITE_KEY'),
        ];
        return view('CheckoutPage/templates/CheckoutPage', $args);
    }



    /* Create */

    public function createUserAddress($params) {

        $user_id    = UserService::getCurrentUserId();
        $city       = $params['city'];
        $name       = $params['name'];
        $surname    = $params['surname'];
        $address    = $params['address'];
        $post_code  = $params['post_code'];
        $phone      = $params['phone'];
        $company    = $params['company'];
        $pib        = $params['pib'];

        return AddressService::createAddressUser(
            $user_id,
            $city,
            $name,
            $surname,
            $address,
            $post_code,
            $phone,
            false,
            false,
            $company,
            $pib
        );
    }



    /* Read */

    public function getAllCities($params) {
        $country_id = $params["country_id"];
        return BaseService::getCitiesByCountryId($country_id);
    }

    /**
     * Dohvata ukupnu cenu bez popusta
     *
     * @param   bool   $is_configuration    Da li je konfiguracija
     * @param   int    $configuration_id    Id konfiguracije
     *
     * @return  int                         Ukupna cena
     */
    public function getTotalPriceRetail(bool $is_configuration = false, ?int $configuration_id = null): int {
        $user_id = UserService::getCurrentUserId();
        $cart = $is_configuration === false
            ? ShopService::getUserCartByUserId($user_id)
            : ConfigurationService::getConfigurationByUserIdConfigurationId($user_id, $configuration_id)
        ;
        $total_price    = 0;
        foreach ($cart as $item) {
            $total_price += $item->product->price_retail * $item->quantity;
        }

        return $total_price;
    }

    /**
     * Dohvata ukupnu cenu sa popustom
     *
     * @param   bool   $is_configuration    Da li je konfiguracija
     * @param   int    $configuration_id    Id konfiguracije
     *
     * @return  int                         Ukupna cena
     */
    public function getTotalPriceDiscount(bool $is_configuration = false, ?int $configuration_id = null): int {
        $user_id = UserService::getCurrentUserId();
        $cart = $is_configuration === false
            ? ShopService::getUserCartByUserId($user_id)
            : ConfigurationService::getConfigurationByUserIdConfigurationId($user_id, $configuration_id)
        ;
        $total_price    = 0;
        foreach ($cart as $item) {
            $total_price += $item->product->price_discount * $item->quantity;
        }

        return $total_price;
    }

    /**
     * Obracun cene isporuke
     *
     * @param   bool   $is_configuration    Da li je konfiguracija
     * @param   int    $configuration_id    Id konfiguracije
     *
     * @return  int                         Ukupna cena
     */
    public function calculateShippingFee(bool $is_configuration = false, ?int $configuration_id = null): int {
        $user_id = UserService::getCurrentUserId();
        $cart = $is_configuration === false
            ? ShopService::getUserCartByUserId($user_id)
            : ConfigurationService::getConfigurationByUserIdConfigurationId($user_id, $configuration_id)
        ;
        $addresses      = AddressService::getAddressesByUserId(UserService::getCurrentUserId());
        $cart           = ShopService::getUserCartByUserId(UserService::getCurrentUserId());
        $total_price    = 0;
        $shipping_fee   = 0;
        foreach ($cart as $item) {
            $total_price += $item->product->price_retail * $item->quantity;
        }

        foreach ($addresses as $address) {
            if ($address->preferred_address_delivery === true) {
                $shipping_fee = ShopService::getShippingFee($total_price);
            }
        }

        return $shipping_fee;
    }


    /*Update*/






    /*Delete*/
}
