<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Components\Cart;
use App\Providers\PermissionService;
use App\Providers\SessionService;
use App\Providers\ShopService;
use App\Providers\UserService;
use App\Providers\WishListService;
use DateTime;
use DateInterval;


/**
 * Kreiranje, statistika i manipulacija postojecih korisnika
 */
class AdminUsers extends BaseComponent {
    protected $css = [
        'AdminUsers/css/AdminUsersList.css',
    ];

    protected $js = [
        'AdminUsers/js/AdminUsersCreate.js',

        'AdminUsers/js/AdminUsersDialogDelete.js',
        'AdminUsers/js/AdminUsersDialogEdit.js',
        'AdminUsers/js/AdminUsersDialogLogins.js',
        'AdminUsers/js/AdminUsersDialogPassword.js',
        'AdminUsers/js/AdminUsersDialogWishlist.js',
        'AdminUsers/js/AdminUsersDialogCart.js',

        'AdminUsers/js/AdminUsersStats.js',
        'AdminUsers/js/AdminUsersList.js',
    ];

    public function renderHTML() {
        $cart = new Cart();
        $args = [
            'permissions' => [
                'user_create'       => PermissionService::checkPermission('user_create'),
                'user_read'         => PermissionService::checkPermission('user_read'),
                'user_update'       => PermissionService::checkPermission('user_update'),
                'user_delete'       => PermissionService::checkPermission('user_delete'),
            ],
            'csrf_field'         => SessionService::getCsrfField(),
        ];

        return
            view('AdminUsers/templates/AdminUsersCreate', $args)
            . view('AdminUsers/templates/AdminUsersList', $args)
            . view('AdminUsers/templates/AdminUsersDialogDelete', $args)
            . view('AdminUsers/templates/AdminUsersDialogEdit', $args)
            . view('AdminUsers/templates/AdminUsersDialogLogins', $args)
            . view('AdminUsers/templates/AdminUsersDialogPassword', $args)
            . view('AdminUsers/templates/AdminUsersDialogWishlist', $args)
            . view('AdminUsers/templates/AdminUsersDialogCart', $args)
            . view('AdminUsers/templates/AdminUsersStats', $args)
        ;
    }










    /**
     * Pravi novog korisnika
     * @param   string  $params['username']             Korisnicko ime
     * @param   string  $params['email']                Email korisnika
     * @param   string  $params['password']             Sifra korisnika
     * @param   string  $params['name']                 Pravo ime korisnika
     * @param   string  $params['surname']              Prezime korisnika
     * @param   string  $params['addres_of_living']     Adresa stanovanja
     * @param   string  $params['address_of_delivery']  Adresa isporuke
     * @param   integer $params['home_phone']           Fiksni telefon
     * @param   integer $params['mobile_phone']         Mobilni telefon
     * @return  integer                     error_code prilikom izvrsavanja funkcije
     */
    public function createUser($params) {
        $username               = $params['username'];
        $email                  = $params['email'];
        $password               = $params['password'];
        $name                   = $params['name'];
        $surname                = $params['surname'];
        $addres_of_living       = $params['address_of_living'];
        $address_of_delivery    = $params['address_of_delivery'];
        $mobile_phone           = $params['mobile_phone'];

        return UserService::signUpLocal(
            $username,
            $email,
            $password,
            $name,
            $surname,
            $mobile_phone
        );
    }




    /**
     * Dohvata informacije o korisnicima
     * @param   string  $params['search']       Filtriranje korisnika po zadatom terminu
     *                                          (traze se mejlovi i korisnicka imena)
     * @param   string  $params['user_id']      ID od kog da krenemo dohvatanje
     * @param   boolean $params['direction']    Smer u kom zelimo da dohvatamo (true za napred, false za nazad)
     * @param   integer $params['limit']        Koliko korisnika da se dohvati
     * @return  array                           Niz korisnika
     */
    public function fetchData($params) {
        $search         = $params['search'];
        $user_id        = intval($params['user_id']);
        $direction      = boolval($params['direction']);
        $limit          = intval($params['limit']);

        $user_id = $user_id === 0 ? null : $user_id;

        return UserService::getUsers($user_id, $search, $direction, $limit);
    }

    /**
     * Dohvata statistike o korisnicima na sajtu
     * @return  array                       Statistika
     */
    public function fetchStats() {
        return [
            // Ukupan broj korisnika
            'nrUsers'           => UserService::getNrUsers(),
            // Ukupan broj korinika kojima je nalog trenutno blokiran
            'nrUsersBanned'     => UserService::getNrUsersBanned(),
            // Broj korisnika koji su trenutno na sajtu
            'nrUsersCurrent'    => UserService::getNrUsersCurrent(),
        ];
    }

    public function fetchUser($params) {
        $user_id = intval($params['user_id']);
        return UserService::getUserByID($user_id);
    }

    /**
    * Dohvata listu zelja za zadatog korisnika
    * @param  int $params id korisnika za kog se trazi
    * @return kolekcija listi zelja
    */
    public function fetchWishlist($params) {
        $user_id = intval($params['user_id']);
        return WishListService::getWishListByUserId($user_id);
    }


    /**
    * Dohvata listu zelja za zadatog korisnika
    * @param  int $params id korisnika za kog se trazi
    * @return kolekcija listi zelja
    */
    public function fetchCart($params) {
        $user_id = intval($params['user_id']);
        return ShopService::getUserCartByUserId($user_id);
    }


    /**
     * Proverava da li postoji korisnik sa datim email-om
     * @param   string      $params['email']    Email koji proveravamo
     * @return  boolean                         Da li postoji ili ne
     */
    public function isEmailTaken($params) {
        $email = $params['email'];
        return UserService::isLocalEmailTaken($email);
    }

    /**
     * Proverava da li postoji korisnik sa datim korisnickim imenom
     * @param   string      $params['username'] Ime koje proveravamo
     * @return  boolean                         Da li postoji ili ne
     */
    public function isUsernameTaken($params) {
        $username = $params['username'];
        return UserService::isLocalUsernameTaken($username);
    }











    /**
     * Promena informacija korisnika
     * @param   string  $params['username']             Promena korisnickog imena
     * @param   string  $params['email']                Promena email korisnika
     * @param   string  $params['name']                 Promena ime korisnika
     * @param   string  $params['surname']              Promena prezime korisnika
     * @param   string  $params['address_of_living']    Promena adresa stanovanja
     * @param   string  $params['address_of_delivery']  Promena adresa isporuke
     * @param   integer $params['home_phone']           Promena fiksni telefon
     * @param   integer $params['mobile_phone']         Promena mobilni telefon
     **/
    public function updateInfo($params) {
        $user_id        = intval($params['user_id']);

        $updates = [
            'username'  => $params['username'],
            'email'     => $params['email'],
        ];

        if (!empty($params['name'])) {
            $updates ['name']                   = $params['name'];
        }

        if (!empty($params['surname'])) {
            $updates ['surname']                = $params['surname'];
        }

        if (!empty($params['address_of_living'])) {
            $updates ['address_of_living']      = $params['address_of_living'];
        }

        if (!empty($params['address_of_delivery'])) {
            $updates ['address_of_delivery']    = $params['address_of_delivery'];
        }

        if (!empty($params['home_phone'])) {
            $updates ['phone_nr']               = $params['home_phone'];
        }

        return UserService::updateLocalUser($user_id, $updates);
    }

    /**
     * Promena lozinke
     * @param   integer     $params['user_id']  ID korisnika kome menjamo lozinku
     * @param   string      $params['password'] Nova lozinka za korinika
     * @return  array                           Azuriran User model, konvertovan u niz
     */
    public function updatePassword($params) {
        $user_id    = intval($params['user_id']);
        $password   = $params['password'];

        return UserService::updateLocalUser($user_id, [
            'password'  => $password,
        ]);
    }

    public function updateImage($params) {
        $user_id = intval($params['user_id']);
        $profile_picture = $params['profile_picture'];
        return UserService::updateLocalUser($user_id, [
            'profile_picture' => $profile_picture
        ]);
    }

    /**
     * Trajno blokira/odblokira korisnicki nalog
     * @param   integer     $params['user_id']  ID korisnika kog blokiramo
     * @param   boolean     $params['status']   Da li blokiramo (true) ili odblokiramo nalog (false)
     * @return  array                           Azuriran User model, konvertovan u niz
     */
    public function updateStatus($params) {
        $user_id = intval($params['user_id']);
        $status = intval($params['status']);
        return UserService::updateBanned($user_id, $status);
    }

    /**
     * Blokira korisnicki nalog na zadat broj dana
     * @param   integer     $params['user_id']  ID korisnika kog blokiramo
     * @param   integer     $params['length']   Na koliko dana blokiramo nalog
     * @return  array                           Azuriran User model, konvertovan u niz
     */
    public function banTemporarily($params) {
        $user_id = intval($params['user_id']);
        $length = intval($params['length']);

        $date = new DateTime();
        $date = $date->add(new DateInterval('P' . $length . 'D'));
        return UserService::updateBanned($user_id, $date);
    }

    public function sendMail($params) {
        $user_id = intval($params['user_id']);

        $user = UserService::getUserByID($user_id);
        return UserService::sendValidateEmail($user->email);
    }

    public function changePassword($params) {
        $user_id    = intval($params['user_id']);
        $user       = UserService::getUserByID($user_id);
        $email      = $user->email;

        $response = UserService::resetPassword($email);
        $params['link'] = $response;
        return $response;
    }










    /**
     * Brisanje korisnika
     * @param   integer     $params['user_id']  Korisnik kojeg zelimo da obrisemo
     * @return  boolean                         Da li je sve OK proslo
     */
    public function deleteUser($params) {
        $user_id = intval($params['user_id']);

        return UserService::deleteUser($user_id);
    }

    public function deleteCartItem($params) {
        $user_id = intval($params['user_id']);
        $product_id = intval($params['product_id']);
        return ShopService::deleteCartItem($user_id, $product_id);
    }

    public function deleteWishlistItem($params) {
        $wish_id = intval($params['wish_id']);
        return WishlistService::deleteFromList($wish_id);
    }
}
