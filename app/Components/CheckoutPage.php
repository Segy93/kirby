<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\AddressService;
use App\Providers\BaseService;
use App\Providers\SessionService;
use App\Providers\ShopService;
use App\Providers\UserService;


/**
 * Strana kase
 */
class CheckoutPage extends BaseComponent {

    protected $css        = ['CheckoutPage/css/CheckoutPage.css'];
    protected $js         = ['CheckoutPage/js/CheckoutPage.js'];

    public function renderHTML() {
        $user =  UserService::getCurrentUser();
        $user_id = UserService::getCurrentUserId();
        $args = [
            'cart'                  => ShopService::getUserCartByUserId($user_id),
            'csrf_field'            => SessionService::getCsrfField(),
            'errors'                => SessionService::getSessionValueForService('checkoutErrors', 'HomeController'),
            'payment'               => ShopService::getPaymentMethods(),
            'shops'                 => ShopService::getShops(),
            'total_price_retail'    => $this->getTotalPriceRetail(),
            'total_price_discount'  => $this->getTotalPriceDiscount(),
            'shipping_fee'          => $this->calculateShippingFee(),
            'user'                  => $user ? $user : null,
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
     * @return  int                         Ukupna cena
     */
    public function getTotalPriceRetail(): int {
        $user_id = UserService::getCurrentUserId();
        $cart = ShopService::getUserCartByUserId($user_id);
        $total_price    = 0;
        foreach ($cart as $item) {
            $total_price += $item->product->price_retail * $item->quantity;
        }

        return $total_price;
    }

    /**
     * Dohvata ukupnu cenu sa popustom
     *
     * @return  int                         Ukupna cena
     */
    public function getTotalPriceDiscount(): int {
        $user_id = UserService::getCurrentUserId();
        $cart = ShopService::getUserCartByUserId($user_id);
        $total_price    = 0;
        foreach ($cart as $item) {
            $total_price += $item->product->price_discount * $item->quantity;
        }

        return $total_price;
    }

    /**
     * Obracun cene isporuke
     *
     * @return  int                         Ukupna cena
     */
    public function calculateShippingFee(): int {
        $user_id = UserService::getCurrentUserId();
        $cart = ShopService::getUserCartByUserId($user_id);
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
