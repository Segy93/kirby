<?php

namespace App\Providers;

use App\Providers\ValidationService;
use App\Providers\RoleService;
use App\Providers\PermissionService;
use App\Models\Admin;
use App\Exceptions\PermissionException;
use App\Exceptions\ValidationException;

class AdminService extends BaseService {

    /**
     *
     * CREATE
     *
     */

    /**
     * Registracija administratora
     * @param   int         $role_id        Id uloge
     * @param   string      $username       Korisničko ime
     * @param   string      $email          Email adresa
     * @param   string      $password       Lozinka
     * @return  Admin|int   $admin          Vraća administratora ako se uspešno registrovao
     *                                      inače vraća neki error kod
     */
    public static function signUp($role_id, $username, $email, $password = null) {
        if (PermissionService::checkPermission('admin_create') === false) {
            throw new PermissionException('Nemate dozvolu za kreiranje administratora', 2001);
        }

        //Validacija id-a uloge
        $role_id = ValidationService::validateInteger(
            $role_id,
            ValidationService::$RANGE_INTEGER_UNSIGNED['min'],
            ValidationService::$RANGE_INTEGER_UNSIGNED['max']
        );

        if ($role_id === false) {
            throw new ValidationException('Id uloge nije odgovarajućeg formata', 2002);
        }

        //Validacija korisničkog imena
        $username = ValidationService::validateString($username, 127);
        if ($username === false) {
            throw new ValidationException('Korisničko ime nije odgovarajućeg formata', 2003);
        }

        //Validacija email adrese
        if (ValidationService::validateEmail($email, 127) === false) {
            throw new ValidationException('Email nije odgovarajućeg formata', 2004);
        }

        if (empty($password)) {
            $password = str_random(10);
        }

        $password = empty($password) ? str_random(10) : $password;

        //Validacija lozinke
        $password = ValidationService::validatePassword($password, 127);
        if ($password === false) {
            throw new ValidationException('Lozinka nije odgovarajućeg formata', 2005);
        }

        //Instaciranje objekta admin-a
        $admin = new Admin();

        //Dohvatam ulugu
        $role = RoleService::getRoleById($role_id);

        //Set-ovanje propertija
        $admin->role        = $role;
        $admin->username    = $username;
        $admin->email       = $email;
        $admin->password    = password_hash($password, PASSWORD_DEFAULT);

        //Čuvanje administratora u bazi
        self::$entity_manager->persist($admin);
        self::$entity_manager->flush();

        return $admin;
    }

    /**
     *
     * READ
     *
     */

    /**
     * Prijava administratora
     * @param   string      $username_email     Korisničko ime ili email adresa administratora
     * @param   string      $password           Lozinka administratora
     * @return  bool        Vraća true ako je administrator uspešno prijavljen u suprotnom vraća neki error kod
     */
    public static function logIn($username_email, $password) {
        //Dohvata administratora po korisničkom imenu ili email adresi
        $admin = self::getAdminByUsernameEmail($username_email);
        if (empty($admin)) {
            throw new PermissionException(
                'Administrator sa tim korisničkim imenom ili email adresom nije pronađen',
                2006
            );
        }

        //Proverava lozinku
        if (password_verify($password, $admin->password)) {
            $_SESSION['admin_service']['current_admin'] = $admin->id;
        } else {
            throw new PermissionException('Pogrešna lozinka', 2007);
        }

        return true;
    }

    /**
    * Služi da vrati trunetno prijavljenog admina
    * @return   Admin   $admin   pronađen admin
    * @return   bool    false    ako je nije našao admina
    */
    public static function getCurrentAdmin() {
        $admin_id = self::getCurrentAdminId();
        return self::$entity_manager->find('App\Models\Admin', $admin_id);
    }

    /**
     * Dohvata administratore
     * @return  array   Vraća niz administratora
     */
    public static function getAdmins() {
        if (PermissionService::checkPermission('admin_read') === false) {
            return [self::getCurrentAdmin()];
        }

        $qb = self::$entity_manager->createQueryBuilder();

        return $qb->select('a')->from('App\Models\Admin', 'a')
        ->getQuery()->getResult();
    }

    /**
     * Vraća dali je administrator prijavljen
     * @return  boolean     Vraća true ako je prijavljen u suprotnom vraća false
     */
    public static function isAdminLoggedIn(): bool {
        return self::getCurrentAdminId() !== false;
    }

    /**
     * Odjava administratora koji je trenutno prijavljen
     * @return void
     */
    public static function logOut() {
        if (self::isAdminLoggedIn()) {
            unset($_SESSION['admin_service']['current_admin']);
            if (session_status() !== PHP_SESSION_NONE) {
                session_destroy();
            }
        }
    }

    /**
     * Dohvata korisnika po korisničkom imenu ili email adresi
     * @param   string      $username_email         Korsničko ime ili email adresa
     * @return  Admin       Vraća admina ako je pronađen ili error code
     */
    private static function getAdminByUsernameEmail($username_email) {
        $qb = self::$entity_manager->createQueryBuilder();

        return $qb->select('a')->from('App\Models\Admin', 'a')
        ->where('a.username = ?1')
        ->setParameter(1, $username_email)
        ->orWhere('a.email = ?2')
        ->setParameter(2, $username_email)
        ->setMaxResults(1)
        ->getQuery()->getOneOrNullResult();
    }

    /**
     * Dohvata administratora po id-u
     * @param   int    $admin_id Id adminstratora
     * @return  Admin           [description]
     */
    public static function getAdminById($admin_id) {
        if (PermissionService::checkPermission('admin_read') === false &&
            PermissionService::checkPermission('article_update_author') === false &&
            $admin_id !== self::getCurrentAdminId()
        ) {
            throw new PermissionException('Nemate dozvolu za dohvatanje administratora po id-u', 2010);
        }

        return self::$entity_manager->find('App\Models\Admin', $admin_id);
    }

    /*
    * Dohvata admina koji je autor teksta radi isto sto i funkcija iznad
    * ali da ne bih ubijao sigurnost na svim mestima koristim ovu.
    */
    public static function getAuthor($username) {
        $qb = self::$entity_manager->createQueryBuilder();
        return $qb->select('a')->from('App\Models\Admin', 'a')
        ->where('a.username = ?1')
        ->setParameter(1, $username)
        ->setMaxResults(1)
        ->getQuery()->getOneOrNullResult();
    }

    /**
     * Dohvata trenutan id admina
     * @return  int     Id admina
     */
    public static function getCurrentAdminId() {
        return isset($_SESSION['admin_service']['current_admin']) ? $_SESSION['admin_service']['current_admin'] : false;
    }

    /**
     *
     * UPDATE
     *
     */

    /**
     * Izmena adminstratora
     * @param   int     $admin_id       Id administratora
     * @param   array       $updates        Niz sa izmenama
     * @return  bool        Vraća true ako je sve poršlo uredu u suprotnom vraća neki error kod
     */
    public static function updateAdmin($admin_id, $updates) {
        if (PermissionService::checkPermission('admin_update') === false) {
            throw new PermissionException('Nemate dozvolu za kreiranje administratora', 2011);
        }
        $admin = self::getAdminById($admin_id);
        if (empty($admin)) {
            throw new ValidationException('Administrator pod tim id-om nije pronađen', 2012);
        }

        //Provera dali postoji ključ role_id
        if (array_key_exists('role_id', $updates)) {
            //Validacija id uloge
            $updates['role_id'] = ValidationService::validateInteger(
                $updates['role_id'],
                ValidationService::$RANGE_INTEGER_UNSIGNED['min'],
                ValidationService::$RANGE_INTEGER_UNSIGNED['max']
            );

            if ($updates['role_id'] === false) {
                throw new ValidationService("Id uloge nije odgovarajućeg formata", 2013);
            }

            //Dohvatanje uloge po id-u
            $role = RoleService::getRoleById($updates['role_id']);
            if (empty($role)) {
                throw new ValidationException('Uloga pod tim id-om nije pronađena', 2014);
            }

            //Setovanje uloge
            $admin->role = $role;
        }

        //Provera dali postoji ključ username
        if (array_key_exists('username', $updates)) {
            //Validacija korisničkog imena
            $updates['username'] = ValidationService::validateString($updates['username'], 127);
            if ($updates['username'] === false) {
                throw new ValidationException('Korsničko ime nije odgovarajućeg formata', 2015);
            }

            //Setovanje korisničkog imena
            $admin->username = $updates['username'];
        }

        //Provera dali postoji ključ email
        if (array_key_exists('email', $updates)) {
            //Verifikacija email-a
            if (ValidationService::validateEmail($updates['email'], 127) === false) {
                throw new ValidationException('Email nije odgovarajućeg formata', 2016);
            }

            //Setovanje email-a
            $admin->email = $updates['email'];
        }

        //Proverava dali postoji kjuč password
        if (array_key_exists('password', $updates)) {
            //Validacija password-a
            $updates['password'] = ValidationService::validatePassword($updates['password'], 127);
            if ($updates['password'] === false) {
                throw new ValidationException('Lozinka nije odgovarajućeg formata', 2017);
            }

            //Setovanje password-a
            $admin->password = password_hash($updates['password'], PASSWORD_DEFAULT);
        }

        //Proverava dali su izmene prazne
        if (!empty($updates)) {
            //Čuvanje izmenjenog administratora u bazi
            self::$entity_manager->persist($admin);
            self::$entity_manager->flush();
        }

        return true;
    }

    public static function resetPassword($admin_id) {
        if (PermissionService::checkPermission('admin_update') === false) {
            throw new PermissionException('Nemate dozvolu za kreiranje administratora', 2011);
        }

        $admin = self::getAdminById($admin_id);
        if (!empty($admin)) {
            $password = str_random(10);
            $admin->password = password_hash($password, PASSWORD_DEFAULT);
            self::$entity_manager->persist($admin);
            self::$entity_manager->flush();
            return $password;
        } else {
            return false;
        }
    }

    /**
     *
     * DELETE
     *
     */

    /**
     * Briše administratora
     * @param   int     $admin_id       Id administratora
     * @return  bool        Vraća true ako je administrator uspešno obrisan u suprotnom vraća neki error kod
     */
    public static function deleteAdmin($admin_id) {
        if (PermissionService::checkPermission('admin_delete') === false) {
            throw new PermissionException('Nemate dozvolu za brisanje administratora', 2018);
        }

        //Dohvata administratora po id-u
        $admin = self::getAdminById($admin_id);
        if (empty($admin)) {
            throw new ValidationException('Administrator pod tim id-om nije pronađen', 2019);
        }

        //Briše administratora
        self::$entity_manager->remove($admin);
        self::$entity_manager->flush();

        return true;
    }
}
