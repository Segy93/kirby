<?php

namespace App\Providers;

use App\Exceptions\DatabaseException;
use App\Exceptions\PermissionException;
use App\Exceptions\ValidationException;
use App\Models\User;
use App\Models\UserLocal;
use App\Providers\PermissionService;
use App\Providers\SessionService;
use App\Providers\ShopService;

class UserService extends BaseService {

    // Za koliko ističe token za aktivaciju
    private static $activation_token_expired = '2 weeks';

    // Za koliko ističe token za reset lozinke
    private static $password_reset_expired = '8 hours';

    // Standardna profilna slka
    private static $profile_picture_path_default = '/default_pictures/default_user_picture.jpg';

    private static $profile_picture_path = 'uploads_static/originals/';

    protected static $service = 'UserService';

    /**
     * Koliko dugo ce korisnik ostati zapamcen ukoliko cekira "Zapamti me" prilikom prijave
     *
     * @var integer
     */
    private static $remember_for = 60 * 60 * 24 * 7;

    /**
     * Koje ce ime kolacic za sesiju imati ukoliko to nije specificirano u .env fajlu
     *
     * @var string
     */
    private static $cookie_name_default = 'Exelence';

    /**
     * Minimalna dužina lozinke
     *
     * @var integer
     */
    public static $PASSWORD_MIN_LENGTH = 6;

    /**
     * Maksimalna dužina lozinke
     *
     * @var integer
     */
    public static $PASSWORD_MAX_LENGTH = 127;










    /**
     *
     * CREATE
     *
     */

    /**
     * Kreira korisnika
     * @param   string      $username               Korisničko ime
     * @param   string      $email                  Email korisnika
     * @param   string      $password               Lozinka korisnika
     * @param   string      $name                   Ime korisnika
     * @param   string      $surname                Prezime korisnika
     * @param   string      $address_home           Adresa stanovanja
     * @param   string      $address_delivery       Adresa dostave
     * @param   string      $phone                  Telefonski broj korisnika
     * @return  UserLocal   $userLocal              Ako je sve prošlo u redu vraća UserLocal model
     *                                              u suprotnom vraća error_code
     */
    public static function signUpLocal(
        $username,
        $email,
        $password,
        $name = null,
        $surname = null,
        $phone_nr = null,
        $profile_picture = null
    ) {
        //Validacija korisničkog imena
        $username = ValidationService::validateString($username, 127, true);
        if ($username === false) {
            throw new ValidationException('Korisničko ime nije odgovarajućeg formata', 22001);
        }

        //Validacija email adrese
        if (ValidationService::validateEmail($email, 127) === false) {
            throw new ValidationException('Email nije odgovarajućeg formata', 22002);
        }

        //Validacija lozinke
        $password = ValidationService::validatePassword($password, self::$PASSWORD_MAX_LENGTH);

        //Provera da li postoji ime
        if (!empty($name)) {
            //Validacija imena
            $name = ValidationService::validateString($name, 63, true);
            if ($name === false) {
                throw new ValidationException('Ime nije odgovarajućeg formata', 22003);
            }
        }

        //Provera da li postoji prezime
        if (!empty($surname)) {
            //Validacija prezimena
            $surname = ValidationService::validateString($surname, 63, true);
            if ($surname === false) {
                throw new ValidationException('Prezime nije odgovarajućeg formata', 22004);
            }
        }

        // Proverava da li postoji broj telefona
        if (!empty($phone_nr)) {
            //Valiacija broja telelfona
            $phone_nr = ValidationService::validatePhoneNumber($phone_nr);
            if ($phone_nr === false) {
                throw new ValidationException('Broj telefona nije odgovarajućeg formata', 22005);
            }
        }

        // Proverava da li postoji profilna slika
        if (!empty($profile_picture)) {
            // Validacija profilne slike
            $profile_picture = ValidationService::validateString($profile_picture, 127, true);
            if ($profile_picture === false) {
                throw new ValidationException('Profilna slika nije odgovarajućeg formata', 22006);
            }
        }


        $user_local = new UserLocal();

        // Obavezni parametri
        $user_local->username                    =   $username;
        $user_local->email                       =   $email;
        $user_local->password                    =   password_hash($password, PASSWORD_DEFAULT);

        // Opcionalni parametri
        if (!empty($name)) {
            $user_local->name                =  $name;
        }

        if (!empty($surname)) {
            $user_local->surname             =  $surname;
        }

        if (!empty($phone_nr)) {
            $user_local->phone_nr            =  $phone_nr;
        }

        if (!empty($profile_picture)) {
            $user_local->profile_picture     =  $profile_picture;
        }

        // $cookie_accepted = SessionService::getSessionValueForService('cookies_accepted', 'user_service');
        // if ($cookie_accepted !== null) {
        //     $user_local->cookies_accepted = 1;
        // }

        //$user_local->cookies_accepted = 0;
        self::$entity_manager->persist($user_local);
        self::$entity_manager->flush();

        // Slanje email-a za validaciju korisničkog email-a
        self::sendValidateEmail($email, true);

        return $user_local;
    }

    /**
     * Kreira privremenog korisnika
     * @param   string      $name           Ime korisnika
     * @param   string      $surname        Prezime korisnika
     * @param   string      $phone_nr       Broj telefona korisnika
     * @return  UserLocal                   Kreiran korisnik
     */
    public static function createTemporaryUser($email) {
        $email_valid = ValidationService::validateEmail($email);
        if ($email_valid === false) {
            throw new ValidationException('Email nije odgovarajućeg formata', 22007);
        }

        $taken      = self::isLocalEmailTaken($email);
        $password   = str_random(10);
        $username   = explode('@', $email)[0];

        if ($taken) {
            throw new ValidationException('Email je zauzet', 22007);
        }

        $user_temp = new UserLocal();
        $user_temp->email    = $email;
        $user_temp->password = password_hash($password, PASSWORD_DEFAULT);
        $user_temp->username = $username;

        self::$entity_manager->persist($user_temp);
        self::$entity_manager->flush();

        UserService::sendValidateEmail($email);
        self::setCurrentUserId($user_temp->id);

        return $user_temp;
    }

    /**
     * Postavlja url za vraćanje
     * @param   string  $url    Url na koji korisnik treba da se vrati
     * @return  void
     */
    public static function setReturnUrl($url) {
        $base = basename($url);
        if ($base !== '' &&
            $base !== 'prijava' &&
            $base !== 'login' &&
            $base !== 'registracija' &&
            $base !== 'comment_post_new' &&
            strpos($base, '.') === false
        ) {
            self::setSession('return_url', $url);
        }
    }



    /**
     *
     * READ
     *
     */


    /**
     * Naziv kolacica koji ce se koristiti za identifikocanje sesije
     *
     * @return string
     */
    public static function getCookieName(): string {
        return env('COOKIE_NAME', self::$cookie_name_default);
    }


    /**
     * Dohvata url na koji treba korisnika da se vrati
     * @return  string      Vraća url ili null
     */
    public static function getReturnUrl() {
        return self::getSessionKeySubKeyValue('return_url');
    }

    /**
     * Vraća id trenutnog korisnika
     * @return  integer      Id korisnika
     */
    public static function getCurrentUserId() {
        return isset($_SESSION['user_service']['current_user']) ? $_SESSION['user_service']['current_user'] : false;
    }

    public static function setCurrentUserId($id) {
        return  $_SESSION['user_service']['current_user'] = $id;
    }

    /**
     * Dohvata trenutno prijavljenog korisnika
     * @return  User      Objekat korisnika
     */
    public static function getCurrentUser() {
        try {
            return self::getUserById(self::getCurrentUserId());
        } catch (DatabaseException $e) {
            self::logOutLocal();
            return false;
        }
    }

    public static function getProfilePicturePath() {
        return self::$profile_picture_path;
    }

    public static function getProfilePicturePathDefault() {
        return self::$profile_picture_path_default;
    }

    /**
     * Vraća da li je korisnik prijavljen
     * @return  boolean     True ako je prijavljen false ako nije
     */
    public static function isUserLoggedIn() {
        return is_numeric(self::getCurrentUserId());
    }

    public static function isUserBanned() {
        $id         = self::getCurrentUserId();
        $user       = self::getUserById($id);
        $now        = new \DateTime();
        return $user->status !== 0 && $user->banned === null || $user->banned !== null && $user->banned > $now;
    }

    public static function hasBanExpired() {
        $id         = self::getCurrentUserId();
        $user       = self::getUserById($id);
        $now        = new \DateTime();
        return $user->banned !== null && $user->banned < $now;
    }

    public static function removeExpiredBan() {
        $id           = UserService::getCurrentUserId();
        $user         = UserService::getUserById($id);
        $now          = new \DateTime();

        $user->banned = null;
        $user->status = 0;
        self::$entity_manager->persist($user);
        self::$entity_manager->flush();

        return true;
    }

    /**
     * Dohvata korisnika po id-u
     * @param   int         $user_id    Id korisnika
     * @return  User        Vraća korisnika
     */
    public static function getUserById($user_id) {
        if (PermissionService::checkPermission('user_read') === false && $user_id !== self::getCurrentUserId()) {
            throw new PermissionException('Nemate dozvolu za dohvatanje korisnika po id-u', 22008);
        }

        $user = self::$entity_manager->find('App\Models\User', $user_id);

        // if (empty($user)) {
        //     throw new DatabaseException('Korisnik sa tim id-jem nije pronađen', 22014);
        // }

        return $user;
    }

    /**
     * Dohvata sve korisnike
     * @return  array      Vraća niz korisnika
     */
    public static function getUsers(
        $user_id = null,
        $search = null,
        $direction = true,
        $limit = null,
        $as_array = false
    ) {
        if (PermissionService::checkPermission('user_read') === false) {
            throw new PermissionException('Nemate dozvolu za dohvatanje korisnika', 22009);
        }

        $orderParameter = $direction ? 'DESC' : 'ASC';

        $qb = self::$entity_manager->createQueryBuilder();

        $users = $qb
            ->select('u')
            ->from('App\Models\User', 'u')
            ->orderBy('u.id', $orderParameter)
        ;

        if (!empty($user_id)) {
            $direction = $direction ? '<' : '>';

            $query = 'u.id ' . $direction . ' :user_id';

            $users
                ->where($query)
                ->setParameter('user_id', $user_id)
            ;
        }

        if (!empty($search)) {
            $users
            ->join('u.local', 'ul')
                ->andWhere('u.id = :id')
                ->setParameter('id', $search)
                ->orWhere('u.name LIKE :name')
                ->setParameter('name', '%' . $search . '%')
                ->orWhere('u.surname LIKE :surname')
                ->setParameter('surname', '%' . $search . '%')
                ->orWhere('ul.email LIKE :email')
                ->setParameter('email', '%' . $search . '%')
                ->orWhere('ul.username LIKE :username')
                ->setParameter('username', '%' . $search . '%')
            ;
        }

        if (!empty($limit)) {
            $users
                ->setMaxResults($limit)
            ;
        }

        $result = $as_array ? $users->getQuery()->getArrayResult() : $users->getQuery()->getResult();

        if ($orderParameter === 'ASC') {
            $result = array_reverse($result);
        }

        return $result;
    }

    /**
     * Dohvata broj korisnika
     * @return  int      Broj korisnika
     */
    public static function getNrUsers() {
        if (PermissionService::checkPermission('user_read') === false) {
            throw new PermissionException('Nemate dozvolu za dohvatanje broj korisnika', 22010);
        }

        $query = self::$entity_manager->getUnitOfWork()->getEntityPersister('App\Models\User', 'u');
        return intval($query->count());
    }

    /**
     * Dohvata broj korisnika
     * @return  int      Broj korisnika
     */
    public static function getNrUsersBanned() {
        if (PermissionService::checkPermission('user_read') === false) {
            throw new PermissionException('Nemate dozvolu za dohvatanje broj korisnika', 22010);
        }

        $qb = self::$entity_manager->createQueryBuilder();

        $users = $qb
            ->select('u')
            ->from('App\Models\User', 'u')
            ->where('u.status != :status')
            ->setParameter('status', 0)
            ->getQuery()
            ->getResult()
        ;

        return count($users);
    }

    /**
     * Dohvata aktivne korisnike
     * @return  integer                     Broj aktivnih korisnika
     */
    public static function getNrUsersCurrent() {
        if (PermissionService::checkPermission('user_read') !== true) {
            throw new PermissionException('Nemate dozvolu za trenutnu operaciju', 2);
        }
        $qb = self::$entity_manager->createQueryBuilder();

        $users = $qb
            ->select('u')
            ->from('App\Models\User', 'u')
            ->where('u.last_visited > :date')
            ->setParameter('date', date('Y-m-d H:i:s', strtotime('-5 minutes')))
            ->getQuery()
            ->getResult()
        ;

        return count($users);
    }

    /**
     * Prijava korisnika
     * @param   string      $username_email         Korisničko ime ili email korisnika
     * @param   string      $password               Lozinka korisnika
     * @return  bool/int    Vraća true ako se upspešno prijavio u suprotnom vraća neki error_code
     */
    public static function logInLocal($username_email, $password, $remember = false) {
        $cart       = ShopService::getUserCartByUserId();
        $wishlist   = WishListService::getWishListByUserId();
        $options    = [];
        $user_local = self::getLocalUserByUsernameEmail($username_email);
        if ($user_local === null) {
            throw new PermissionException('Korisnik sa tim korisničkim imenom ne postoji', 1);
        }

        if ($user_local->password === 'nopassword') {
            self::resetPassword($user_local->email, 'MIGRATE');

            throw new PermissionException(
                'Zbog promene sistema kao stari korisnik morate resetovati lozinku, mail za reset vam je poslan',
                22013
            );
        } elseif (password_verify($password, $user_local->password)) {
            $_SESSION['user_service']['current_user'] = $user_local->id;
            if ($remember) {
                $cookie_expire_time = time() + self::$remember_for;
                $cookie_name = self::getCookieName();
                $options['expires'] = $cookie_expire_time;
                $options['secure']  = true;
                $options['samesite'] = 'strict';
                setcookie($cookie_name, $_COOKIE[$cookie_name], $options);
            }
        } else {
            throw new PermissionException('Pogrešna lozinka', 22012);
        }

        if (empty($user_local)) {
            throw new PermissionException('Korisnik sa tim korisničkim imenom ili email-om nije pronađen', 22011);
        }

        if (!empty($cart)) {
            self::moveProductsFromSessionToCart($cart);
        }

        if (!empty($wishlist)) {
            self::moveProductsFromSessionToWishList($wishlist);
        }

        $user = self::getUserById($user_local->id);
        $now  = new \DateTime();

        if (self::hasBanExpired()) {
            self::removeExpiredBan();
        }


        $cookie_accepted = SessionService::getSessionValueForService('cookies_accepted', 'user_service');
        if ($cookie_accepted !== null) {
            $user->cookies_accepted = 1;

            self::$entity_manager->persist($user);
            self::$entity_manager->flush();
        }

        if ($user->status !== 0) {
            if ($user->banned === null) {
                throw new PermissionException('Ovaj nalog je trajno blokiran', 18);
            } else {
                throw new PermissionException('Ovaj nalog je blokiran na određeno vreme', 19);
            }
        }

        return true;
    }

    /**
     * Dohvata korisnika po korisničkom imenu ili email-u
     * @param   string      $username_email         Korisničko ime korisnika ili email
     * @return  UserLocal   Vraća objekat korisnika
     */
    private static function getLocalUserByUsernameEmail($username_email) {
        $qb = self::$entity_manager->createQueryBuilder();

        return $qb
            ->select('u')
            ->from('App\Models\UserLocal', 'u')
            ->where('u.username = ?1')
            ->setParameter(1, $username_email)
            ->orWhere('u.email = ?2')
            ->setParameter(2, $username_email)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public static function isPasswordResetTokenValid(string $token): bool {
        return self::getLocalUserByResetToken($token) !== null;
    }

    public static function getLocalUserByResetToken(string $token): ?User {
        return self::$entity_manager
            ->createQueryBuilder()
            ->select('u')
            ->from('App\Models\UserLocal', 'u')
            ->where('u.password_reset_token = ?1')
            ->setParameter(1, $token)
            ->andWhere('u.password_reset_expired > CURRENT_TIMESTAMP()')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * Proverava da li postoji korisnik sa time korisničkim imenom
     * @param   string      $username       Korisničko ime
     * @return  boolean     true ako je zauzeto korisničko ime, false ako nije
     */
    public static function isLocalUsernameTaken(string $username): bool {
        $username = ValidationService::validateString($username, 63, true);

        $userLocal = self::$entity_manager->createQueryBuilder()
            ->select('u')
            ->from('App\Models\UserLocal', 'u')
            ->where('u.username = ?1')
            ->setParameter(1, $username)
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();

        return !empty($userLocal);
    }

    /**
     * Proverava da li postoji korisnik sa time korisničkim imenom
     * @param   string      $email       Email
     * @return  boolean     true ako je zauzeto korisničko ime, false ako nije
     */
    public static function isLocalEmailTaken(string $email): bool {
        $is_valid = ValidationService::validateEmail($email, 127);

        if ($is_valid) {
            $user = self::$entity_manager->createQueryBuilder()
                ->select('u')
                ->from('App\Models\UserLocal', 'u')
                ->where('u.email = ?1')
                ->setParameter(1, $email)
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult()
            ;

            return !empty($user);
        } else {
            return false;
        }
    }

    /**
     *
     * UPDATE
     *
     */

    /**
     * Izmena korisnika
     * @param   int         $user_id        Id korisnika
     * @param   array       $updates        Niz sa izmenama
     * @return  User        Izmenjen model korisnika
     */
    public static function updateLocalUser(int $user_id, array $updates): User {
        $has_permission = PermissionService::checkPermission('user_update');
        if ($has_permission === false && self::getCurrentUserId() !== $user_id) {
            throw new PermissionException('Nemate dozvolu za izmenu korisnika', 22013);
        }

        //Dohvatam korisnika
        $user = self::getUserById($user_id);
        if (empty($user)) {
            throw new DatabaseException('Korisnik sa tim id-jem nije pronađen', 22014);
        }

        //Proveravam da li postoji ključ u nizu username
        if (array_key_exists('username', $updates)
            && !empty($updates['username'])
            && $user->username !== $updates['username']
        ) {
            $updates['username'] = ValidationService::validateString($updates['username'], 63, true);
            if ($updates['username'] === false) {
                throw new ValidationException('Korisničko ime nije odgovarajućeg formata', 22015);
            }

            $user->username = $updates['username'];
        }

        //Proveravam da li postoji ključ u nizu email
        if (array_key_exists('email', $updates) && !empty($updates['email']) && $user->email !== $updates['email']) {
            if (PermissionService::checkPermission('user_update') === false) {
                throw new ValidationException('Nemate dozvolu za izmenu korisnika', 22013);
            }

            if (ValidationService::validateEmail($updates['email'], 127) === false) {
                throw new ValidationException('Email nije odgovarajućeg formata', 22016);
            }

            $user->email = $updates['email'];
        }

        if (// Proveravam da li postoji ključ u nizu password
            array_key_exists('password', $updates)
            && array_key_exists('password_old', $updates)
            && array_key_exists('password_confirm', $updates)
        ) {
            $updates['password']            = ValidationService::validatePassword($updates['password'], 127);
            $updates['password_old']        = ValidationService::validatePassword($updates['password_old'], 127);
            $updates['password_confirm']    = ValidationService::validatePassword($updates['password_confirm'], 127);

            if ($updates['password'] === false
                || $updates['password_old'] === false
                || $updates['password_confirm'] === false
            ) {
                throw new ValidationException('Lozinka nije odgovarajućeg formata', 22017);
            } elseif (password_verify($updates['password_old'], $user->password) === false) {
                throw new ValidationException('Trenutna lozinka nije ispravna', 22023);
            } elseif ($updates['password'] !== $updates['password_confirm']) {
                throw new ValidationException('Lozinke se ne podudaraju', 22027);
            } elseif (password_verify($updates['password'], $user->password) === true) {
                throw new ValidationException('Trenutna i nova lozinka su iste', 22021);
            } else {
                $user->password = password_hash($updates['password'], PASSWORD_DEFAULT);
                EmailService::passwordUpdated([], $user->email);
            }
        }

        //Proveravam da li postoji ključ u nizu name
        if (array_key_exists('name', $updates)
            && $user->name !== $updates['name']
            && !empty($updates['name'])
        ) {
            $updates['name'] = ValidationService::validateString($updates['name'], 63, true);
            if ($updates['name'] === false) {
                throw new ValidationException('Ime nije odgovarajućeg formata', 22018);
            }

            $user->name = $updates['name'];
        }

        //Proveravam da li postoji ključ u nizu surname
        if (array_key_exists('surname', $updates)
            && $user->surname !== $updates['surname']
            && !empty($updates['surname'])
        ) {
            $updates['surname'] = ValidationService::validateString($updates['surname'], 63, true);
            if ($updates['surname'] === false) {
                throw new ValidationException('Prezime nije odgovarajućeg formata', 22019);
            }
            $user->surname = $updates['surname'];
        }

        //Proveravam da li postoji ključ u nizu phone
        if (array_key_exists('phone_nr', $updates)
            && $user->phone_nr !== $updates['phone_nr']
            && !empty($updates['phone_nr'])
        ) {
            $updates['phone_nr'] = ValidationService::validatePhoneNumber($updates['phone_nr']);
            if ($updates['phone_nr'] === false) {
                throw new ValidationException('Broj telefona nije odgovarajućeg formata', 22022);
            }
            $user->phone_nr = $updates['phone_nr'];
        }

        //Proverava da li postoji ključ u nizu profile_picture
        if (array_key_exists('profile_picture', $updates) && !empty($updates['profile_picture'])) {
            $response = ImageService::uploadImage($updates['profile_picture'], self::$static_originals);
            $user->profile_picture = $response;
        }

        if (array_key_exists('cookies_accepted', $updates)) {
            $is_bool = ValidationService::validateBoolean($updates['cookies_accepted']);
            if ($is_bool) {
                $user->cookies_accepted = $updates['cookies_accepted'];
            }
        }

        //Proveravam da li postoji izmeni i čuvak izmenjenog korisnika u bazu
        if (!empty($updates)) {
            self::$entity_manager->persist($user);
            self::$entity_manager->flush();
        }

        if (array_key_exists('email', $updates)) {
            self::sendValidateEmail($user->email);
        }


        return $user;
    }

    public static function checkCurrentPassword($user_id, $password_old) {
        $user = self::getUserById($user_id);
        if (empty($user)) {
            throw new DatabaseException('Korisnik sa tim id-jem nije pronađen', 22014);
        }

        return password_verify($password_old, $user->password);
    }

    /**
     * Generise link za resetovanje lozinke, cuva ga u bazi i salje mejl korisniku
     * @param   string  $email              Email adresa korinsika
     * @param   string  $reason             Sta je razlog reseta
     *                                      -'REQUEST': korisnik je zahtevao reset
     *                                      -'MIGRATE': u pitanju je korisnik sa starog sajta
     * @return  string  $link               Link na kome moze resetovati lozinku
     */
    public static function resetPassword(string $email, string $reason = 'REQUEST'): string {
        $user = self::getLocalUserByUsernameEmail($email);

        if (empty($user)) {
            throw new ValidationException('Korisnik sa tim email-om nije pronađen', 22024);
        }

        $password_reset_expired = new \DateTime('now');
        $password_reset_expired->modify('+ ' . self::$password_reset_expired);
        $password_reset_token = str_random(15);

        $user->password_reset_token     = $password_reset_token;
        $user->password_reset_expired   = $password_reset_expired;

        self::$entity_manager->persist($user);
        self::$entity_manager->flush();

        $link = route('reset-password-enter-new', [
            'token' => $password_reset_token,
        ]);

        EmailService::sendEmailResetPassword([
            'link' => $link,
            'reason' => $reason,
        ], $email);

        return $password_reset_token;
    }

    /**
     * Resetovanje lozinke za korinsika
     * @param   string      $token              Token koji je korisnik dobio kada je zatražio promenu lozinke
     * @param   string      $password           Nova lozinka
     * @param   string      $password_repeat    Ponovljena nova lozinka
     */
    public static function confirmResetPassword(
        string $token,
        string $password,
        string $password_repeat
    ): void {
        $user = self::getLocalUserByResetToken($token);

        if ($user === null) {
            throw new ValidationException('Korisnik sa tim tokenom nije pronađen', 22025);
        }

        $date_now = new \DateTime('now');
        if ($date_now > $user->password_reset_expired) {
            throw new ValidationException('Token je istekao, ponovo pošalji te zahtev za reset lozinke', 22026);
        }

        if ($password !== $password_repeat) {
            throw new ValidationException('Lozinke se ne podudaraju', 22027);
        }

        $password = ValidationService::validatePassword($password, self::$PASSWORD_MAX_LENGTH);

        $user->password                 = password_hash($password, PASSWORD_DEFAULT);
        $user->password_reset_expired   = null;
        $user->password_reset_token     = null;

        self::$entity_manager->persist($user);
        self::$entity_manager->flush();
        EmailService::passwordUpdated([], $user->email);
    }

    /**
     * Potvrda email-a
     * @param   string      $token      Token za aktivaciju
     * @return  bool/int    Vraća true ako je sve prošlo uredu u suprotnom vraća neki error_code
     */
    public static function confirmEmail($token) {
        try {
            $qb = self::$entity_manager->createQueryBuilder();

            $user = $qb
                ->select('u')
                ->from('App\Models\UserLocal', 'u')
                ->where('u.activation_token = ?1')
                ->setParameter(1, $token)
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult()
            ;

            if (empty($user)) {
                throw new ValidationException('Korisnik sa tim tokenom nije pronađen', 22028);
            }

            $date_now = new \DateTime('now');
            if ($date_now > $user->activation_token_expired) {
                throw new ValidationException(
                    'Token je istekao, ponovo pošaljite zahtev za aktivaciju email-a',
                    22029
                );
            }

            $user->activation_token             =   null;
            $user->activation_token_expired     =   null;
            // Logujem korisnika
            $_SESSION['user_service']['current_user'] = $user->id;

            self::$entity_manager->persist($user);
            self::$entity_manager->flush();

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Šalje email korisniku za validaciju email-a
     * @param   User        $user           Korisnik kome saljemo poruku
     * @return  boolean                     true ako je sve prošlo uredu inače vraća error_code
     */
    public static function sendValidateEmail($email, $is_register = false) {
        $user = self::getLocalUserByUsernameEmail($email);
        if (empty($user)) {
            throw new ValidationException('Korisnik sa tim email-om nije pronađen', 22030);
        }

        $activation_token           =   str_random(15);
        $activation_token_expired   =   new \DateTime('now');
        $activation_token_expired->modify('+ ' . self::$activation_token_expired);

        $user->activation_token             =   $activation_token;
        $user->activation_token_expired     =   $activation_token_expired;

        self::$entity_manager->persist($user);
        self::$entity_manager->flush();

        $link = route('profile', ['username' => $user->username ,'token' => $activation_token]);
        // Moram ovako zato sto mi u suprotnom vrati /korisnik/naziv[/{token}]/token
        $link = preg_replace('/\[/', '', $link);
        $link = preg_replace('/\]/', '', $link);
        $response = EmailService::sendEmailValidation([
            'username'      => $user->username,
            'link'          => $link,
            'is_register'   => $is_register
        ], $email);

        if ($response === false) {
            throw new \Exception('Neuspelo slanje email-a', 22031);
        }

        return true;
    }


    /**
     * Banuje korisnika na privremeno ili trajno
     * @param   int         $user_id            Id korisnika
     * @param   int/date    $banned_status      Status ili datum banovanja
     * @return  bool/int    Vraća true ako je sve prošlo uredu u suprtnom vraća neki error_code
     */
    public static function updateBanned($user_id, $banned_status) {
        if (PermissionService::checkPermission('user_update') === false) {
            throw new PermissionException('Nemate dozvolu da banujete korisnika', 22032);
        }

        //Dohvatam korisnika po id-u
        $user = self::getUserById($user_id);
        if (empty($user)) {
            throw new ValidationException('Korisnik sa tim id-om nepostoji', 22033);
        }

        $message = '';
        //Proveravam da li je status intiger tipa
        if (is_numeric($banned_status)) {
            $user->status = $banned_status;
            if ($banned_status === 0) {
                $user->banned = null;
                $message = 'Niste vise banovani';
            } else {
                $message = 'Banovani ste za stalno';
            }
        }
        //Proveravam da li je status datum tipa
        if (is_a($banned_status, 'DateTime')) {
            $user->status = 1;
            $user->banned = $banned_status;
            $date = $banned_status->format('Y-m-d H:i:s');
            $message = 'Banovani ste do: ' . $date;
        }
        //Čuvam izmene u bazu
        self::$entity_manager->persist($user);
        self::$entity_manager->flush();
        $response = EmailService::sendUserBanned([
            'username'      => $user->username,
            'message'      => $message,
        ], $user->email);
        return true;
    }

    /**
     * Update-ju vreme posete korisnika
     * @return void
     */
    public static function updateVisitTime($user_id) {
        $user = self::getUserById($user_id);
        if (empty($user)) {
            throw new DatabaseException('Korisnik sa tim id-jem nije pronađen', 22014);
        }

        $user->last_visited = new \DateTime();

        self::$entity_manager->persist($user);
        self::$entity_manager->flush();
    }

    /**
     * Odjava korisnika
     * @return void
     */
    public static function logOutLocal() {
        $options = [];
        if (self::isUserLoggedIn()) {
            unset($_SESSION['user_service']['current_user']);
            $cookie_name = self::getCookieName();
            $options['expires'] = time();
            $options['secure']  = true;
            $options['samesite'] = 'strict';
            setcookie($cookie_name, $_COOKIE[$cookie_name], $options);
            if (session_status() !== PHP_SESSION_NONE) {
                session_destroy();
            }
        }
    }

    /**
     * Prebacuje proizvode iz sessije u korpu korisnika
     * @param   array   $cart       Korpa iz sesije
     * @return  void
     */
    private static function moveProductsFromSessionToCart($cart) {
        foreach ($cart as $item) {
            ShopService::changeCart($item->product->id, $item->quantity);
        }

        self::deleteSessionSubkey('cart');
    }



    private static function moveProductsFromSessionToWishList($wishlist) {
        foreach ($wishlist as $item) {
            if (WishListService::getWishListByProductIdUserId(
                $item->product->id,
                UserService::getCurrentUserId()
            ) === null) {
                WishListService::addToList($item->product->id);
            }
        }

        self::deleteSessionSubkey('wishlist');
    }

    /**
     *
     * DELETE
     *
     */

    /**
     * Briše korisnika
     * @param   int         $user_id    Id korisnika koga želite da obrišete
     * @return  bool/int    Vraća true ako je korisnik obrisan u suprotnom vraća neki error code
     */
    public static function deleteUser($user_id) {
        try {
            if (PermissionService::checkPermission('user_delete') === false) {
                throw new PermissionException('Nemate dozvolu za brisanje korisnika', 22034);
            }

            // Dovhata korisnika po id-u
            $user = self::getUserById($user_id);
            if (empty($user)) {
                throw new ValidationException('Korisnik pod tim id-om nije pronađen', 22035);
            }

            AddressService::deleteUserAddresses($user_id);

            // Briše korisnika
            self::$entity_manager->remove($user);
            self::$entity_manager->flush();

            return true;
        } catch (\Exception $e) {
            throw new \Exception('Ne može se obrisati korisnik koji ima narudžbinu', 22036);
        }
    }
}
