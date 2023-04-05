<?php

namespace App\Providers;

use App\Models\Addresses\AddressShop;
use App\Models\Addresses\AddressUser;

use App\Exceptions\PermissionException;
use App\Exceptions\ValidationException;
use App\Providers\UserService;
use App\Providers\ValidationService;
use App\Providers\PermissionService;

class AddressService extends BaseService {

    public static function createAddressShop() {
        if (PermissionService::checkPermission('shop_update') === false) {
            throw new PermissionException('Nemate dozvolu za kreiranje adrese radnje', 1001);
        }

        return false;
    }

    public static function createAddressUser(
        $user_id,
        $city,
        $contact_name,
        $contact_surname,
        $street_address,
        $postal_code,
        $phone_nr,
        $preferred_address_delivery = false,
        $preferred_address_billing = false,
        $company = null,
        $pib = null
    ) {
        if (PermissionService::checkPermission('user_update') === false
            && UserService::getCurrentUserId() !== $user_id
        ) {
            $message = 'Nemate dozvolu za kreiranje adrese korisnika';
            throw new PermissionException($message, 1002);
        }
        $additional = [
            'user_id'                       =>  $user_id,
            'contact_name'                  =>  $contact_name,
            'contact_surname'               =>  $contact_surname,
            'company'                       =>  $company,
            'phone_nr'                      =>  $phone_nr,
            'preferred_address_delivery'    =>  $preferred_address_delivery,
            'preferred_address_billing'     =>  $preferred_address_billing,
            'pib'                           =>  $pib,
        ];
        if ($user_id === false) {
            return self::createAddressInSession($city, $street_address, $postal_code, $additional);
        } else {
            return self::createAddress($city, $street_address, $postal_code, $additional, 'user');
        }
    }

    /**
     *
     * CREATE
     *
     */


    private static function createAddress(
        $city,
        $street_address,
        $postal_code,
        $additional,
        $type
    ) {
        $street_address = ValidationService::validateString($street_address, 127, true);
        if ($street_address === false || empty($street_address)) {
            throw new ValidationException('Adresa nije odgovarajućeg formata', 1004);
        }

        $postal_code = ValidationService::validatePostalCode($postal_code);
        if ($postal_code === false) {
            throw new ValidationException('Poštanski broj nije odgovarajućeg formata', 1005);
        }

        if ($type === 'shop') {
            $address    =   new AddressShop();
        } elseif ($type === 'user') {
            $address    =   new AddressUser();
        }

        $user_id = UserService::getCurrentUserId();
        $addresses = self::getAddressesByUserId($user_id);
        foreach ($addresses as $a) {
            if ($a->address === $street_address) {
                throw new ValidationException('Adresa već postoji');
            }
        }

        $address->city          =   $city;
        $address->address       =   $street_address;
        $address->postal_code   =   $postal_code;


        if ($type === 'shop') {
            $shop = ShopService::getShopById($additional['shop_id']);

            if (ValidationService::validateEmail($additional['email'], 127) === false) {
                throw new ValidationException('Email nije odgovarajućeg formata', 1006);
            }

            if (ValidationService::validatePhoneNumber($additional['fax']) === false) {
                throw new ValidationException('Fax nije odgovarajućeg formata', 1007);
            }

            if (ValidationService::validateString($additional['open_hours'], 255, ['empty_check' => true]) === false) {
                throw new ValidationException('Radni sati nisu odgovarajućeg formata', 1008);
            }

            $additional->shop       =   $shop;
            $address->email         =   $additional['email'];
            $address->fax           =   $additional['fax'];
            $address->open_hours    =   $additional['open_hours'];
        } elseif ($type === 'user') {
            $user = UserService::getUserById($additional['user_id']);

            if (ValidationService::validateString($additional['contact_name'], 63, ['empty_check' => true]) === false) {
                throw new ValidationException('Ime nije odgovarajućeg formata', 1009);
            }

            if (ValidationService::validateString(
                $additional['contact_surname'],
                63,
                ['empty_check' => true]
            ) === false) {
                throw new ValidationException('Prezime nije odgovarajućeg formata', 1010);
            }

            if (!empty($additional['company'])) {
                if (ValidationService::validateString($additional['company'], 63) === false) {
                    throw new ValidationException('Kompanija nije odgovarajućeg formata', 1011);
                }
            }

            if (!empty($additional['pib'])) {
                if (ValidationService::validateInteger($additional['pib']) === false) {
                    throw new ValidationException('Pib nije odgovarajućeg formata', 1011);
                }
            }

            if (ValidationService::validatePhoneNumber($additional['phone_nr']) === false) {
                throw new ValidationException('Broj telefona nije odgovarajućeg formata', 1012);
            }

            $address->user = $user;
            $address->contact_name = $additional['contact_name'];
            $address->contact_surname = $additional['contact_surname'];
            if (!empty($additional['company'])) {
                $address->company = $additional['company'];
            }

            if (!empty($additional['pib'])) {
                $address->pib = $additional['pib'];
            }

            $address->phone_nr = $additional['phone_nr'];
            $address->preferred_address_delivery = ValidationService::validateBoolean(
                $additional['preferred_address_delivery']
            );
            $address->preferred_address_billing = ValidationService::validateBoolean(
                $additional['preferred_address_billing']
            );
            $address->status = false;
        }

        self::$entity_manager->persist($address);
        self::$entity_manager->flush();

        return $address;
    }


    private static function createAddressInSession($city, $street_address, $postal_code, $additional) {
        $address    =   new AddressUser();
        $id = -1;
        $session_addresses      = SessionService::getSessionValueForService('addresses', 'address_service');
        if ($session_addresses !== null) {
            $last_address = end($session_addresses);
            $id = $last_address->id - 1;
        }
        $address->id            =   $id;
        $address->city          =   $city;
        $address->address       =   $street_address;
        $address->postal_code   =   $postal_code;

        if (ValidationService::validateString($additional['contact_name'], 63) === false) {
            throw new ValidationException('Ime nije odgovarajućeg formata', 1009);
        }

        if (ValidationService::validateString($additional['contact_surname'], 63) === false) {
            throw new ValidationException('Prezime nije odgovarajućeg formata', 1010);
        }

        if (!empty($additional['company'])) {
            if (ValidationService::validateString($additional['company'], 63) === false) {
                throw new ValidationException('Kompanija nije odgovarajućeg formata', 1011);
            }
        }

        if (!empty($additional['pib'])) {
            if (ValidationService::validateInteger($additional['pib']) === false) {
                throw new ValidationException('Pib nije odgovarajućeg formata', 1011);
            }
        }

        if (ValidationService::validatePhoneNumber($additional['phone_nr']) === false) {
            throw new ValidationException('Broj telefona nije odgovarajućeg formata', 1012);
        }

        $address->contact_name = $additional['contact_name'];
        $address->contact_surname = $additional['contact_surname'];
        if (!empty($additional['company'])) {
            $address->company = $additional['company'];
        }
        if (!empty($additional['pib'])) {
            $address->pib = $additional['pib'];
        }
        $address->phone_nr = $additional['phone_nr'];
        $address->preferred_address_delivery = ValidationService::validateBoolean(
            $additional['preferred_address_delivery']
        );
        $address->preferred_address_billing = ValidationService::validateBoolean(
            $additional['preferred_address_billing']
        );
        $address->status = false;
        SessionService::setSessionForService('addresses', $address, true, 'address_service');
        return $address;
    }

    public static function createAddressFromSession($address_id) {
        $address = self::getAddressFromSessionWithId($address_id);
        $user    = UserService::getCurrentUser();
        if ($address === null) {
            throw new \Exception('Nema adrese u sesiji');
        }

        unset($address->id);
        $address->user = $user;
        self::$entity_manager->persist($address);
        self::$entity_manager->flush();
        return $address->id;
    }
    /**
     *
     * READ
     *
     */

    /**
     * Dohvata adresu po id-u
     * @param   int         $address_id     Id adrese
     * @return  Address     Vraća objekat adrese
     */
    public static function getAddressById($address_id) {
        $address = self::$entity_manager->find('App\Models\Addresses\Address', $address_id);

        if ($address->address_type === 'user') {
            if (PermissionService::checkPermission('user_update') === false
                && UserService::getCurrentUserId() !== $address->user->id
            ) {
                throw new PermissionException('Nemate dozvolu za dohvatanje adrese korisnika', 1013);
            }
        }

        return $address;
    }

    public static function getAddressFromSessionWithId($address_id) {
        $session_addresses      = SessionService::getSessionValueForService('addresses', 'address_service');
        $address                = null;
        $address_id             = intval($address_id);
        foreach ($session_addresses as $s_address) {
            if ($s_address->id === $address_id) {
                $address = $s_address;
            }
        }

        return $address;
    }

    public static function getUserPreferedAddress($type = 'shipping') {
        $user_id = UserService::getCurrentUserId();
        $qb = self::$entity_manager->createQueryBuilder();
        $address = $qb
            ->select('a')
            ->from('App\Models\Addresses\AddressUser', 'a')
            ->where('a.user_id = :user_id')
            ->setParameter('user_id', $user_id)
        ;
        if ($type === 'shipping') {
            $address
                ->andWhere('a.preferred_address_delivery = 1')
            ;
        } else {
            $address
                ->andWhere('a.preferred_address_billing = 1')
            ;
        }
        return $address->setMaxResults(1)->getQuery()->getOneOrNullResult();
    }

    public static function getAddressActiveByUserId($user_id) {
        $unused = 0;
        $used   = 1;

        $qb = self::$entity_manager->createQueryBuilder();
        $addresses = $qb
            ->select('a')
            ->from('App\Models\Addresses\AddressUser', 'a')
            ->where('a.user_id = :user_id')
            ->andWhere('a.status = :unused OR a.status = :used')
            ->setParameter('user_id', $user_id)
            ->setParameter('unused', $unused)
            ->setParameter('used', $used)
        ;


        //var_dump($addresses->getQuery()->getSql());die;
        $addresses->getQuery()->getResult();
        return $addresses;
    }

    /**
     * Dohvata adrese korisnika
     * @param   int     $user_id    Id korisnika
     * @param   string  $search     Ključna reč za pretragu
     * @param   boolean $direction  Smer u kojem se sortira niz
     * @param   int     $limit      Količina rezultata koje vraćamo
     * @param   boolean $as_array   da li da vrati kao niz ili kolekciju
     * @param   string  $preferred_address   Da li da vrati preferiranu i da li delivery ili billing
     * @param   boolean $active     Da li da vradi sve adrese ili samo ne obrisane
     * @return  array   $result     Niz objekata Address
     */
    public static function getAddressesByUserId(
        $user_id,
        $search = null,
        $direction = null,
        $limit = null,
        $as_array = false,
        $preferred_address = null,
        $active = false
    ) {
        if (PermissionService::checkPermission('user_update') === false
            && UserService::getCurrentUserId() !== $user_id
        ) {
            throw new PermissionException('Nemate dozvolu za dohvatanje adresa korisnika', 1014);
        }

        $user = UserService::getUserById(UserService::getCurrentUserId());
        $order_parameter = $direction ? 'DESC' : 'ASC';

        $qb = self::$entity_manager->createQueryBuilder();

        if ($user === null && $active === false) {
            $address = $qb
                ->select('a')
                ->from('App\Models\Addresses\AddressUser', 'a')
                ->where('a.user_id = :user_id')
                ->orderBy('a.id', $order_parameter)
                ->setParameter('user_id', $user_id)
            ;

            if (!empty($preferred_address)) {
                $address
                    ->andWhere('a.' . $preferred_address . ' = 1')
                ;
            }

            if (!empty($search)) {
                $address
                    ->andWhere('a.id = :search
                    OR a.contact_name LIKE :search
                    OR a.contact_surname LIKE :search
                    OR a.company LIKE :search
                    OR a.phone_nr LIKE :search
                    OR a.address LIKE :search')
                    ->setParameter('search', $search)
                ;
            }

            if (!empty($limit)) {
                $address->setMaxResults($limit);
            }
        } else {
            $unused = 0;
            $used   = 1;

            $address = $qb
                ->select('a')
                ->from('App\Models\Addresses\AddressUser', 'a')
                ->where('a.user_id = :user_id')
                ->andWhere('a.status = :unused OR a.status = :used')
                ->orderBy('a.id', $order_parameter)
                ->setParameter('user_id', $user_id)
                ->setParameter('unused', $unused)
                ->setParameter('used', $used)
            ;

            if (!empty($preferred_address)) {
                $address
                    ->andWhere('a.' . $preferred_address . ' = 1')
                ;
            }

            if (!empty($search)) {
                $address
                    ->andWhere('a.id = :search
                    OR a.contact_name LIKE :search
                    OR a.contact_surname LIKE :search
                    OR a.company LIKE :search
                    OR a.phone_nr LIKE :search
                    OR a.address LIKE :search')
                    ->setParameter('search', $search)
                ;
            }
        }

        $result = $as_array ? $address->getQuery()->getArrayResult() : $address->getQuery()->getResult();

        if ($order_parameter === 'ASC') {
            $result = array_reverse($result);
        }

        return $result;
    }

    /**
     * Lista statusa
     * @return      array       Vraća listu mogućih statusa za adrese
     */
    public static function getAllAddressStatuses() {
        return [
            'unused'    =>  0,
            'used'      =>  1,
            'deleted'   =>  2,
        ];
    }

    /**
     *
     * UPDATE
     *
     */

    /**
     * Izmena adrese
     * @param   int         $address_id     Id adrese
     * @param   array       $updates        Niz sa izmenama
     * @return  bool/int    Vraća true ako je sve prošlo uredu u suprotnom vraća error_code
     */
    public static function updateAddress($address_id, $updates) {
        $unused = 0;
        $used   = 1;
        $deleted = 2;

        $address = self::getAddressById($address_id);
        if (empty($address)) {
            throw new ValidationException('Adresa sa tim id-om nije pronađena', 1015);
        }

        if ($address->address_type === 'user' &&
            PermissionService::checkPermission('user_update') === false &&
            $address->user->id !== UserService::getCurrentUserId()
        ) {
            throw new PermissionException('Nemate dozvolu za izmenu adrese korisnika', 1016);
        } elseif ($address->address_type === 'shop' &&
            PermissionService::checkPermission('shop_update') === false
        ) {
            throw new PermissionException('Nemate dozvolu za izmenu adrese radnje', 1017);
        }

        $user_id = $address->user->id;
        $addresses = self::getAddressesByUserId($user_id);
        $preferred_delivery = null;
        $preferred_billing  = null;

        foreach ($addresses as $a) {
            if ($a->preferred_address_delivery) {
                $preferred_delivery = $a;
            }
            if ($a->preferred_address_billing) {
                $preferred_billing = $a;
            }
            if ($a->id !== $address->id && $a->status !== 2) {
                if (array_key_exists('address', $updates)) {
                    if ($a->address === $updates['address']) {
                        throw new ValidationException('Adresa već postoji');
                    }
                }
            }
        }

        if ($address->address_type === 'shop' || $address->status === $unused) {
            if (array_key_exists('city', $updates)) {
                $address->city = $updates['city'];
            }

            if (array_key_exists('contact_name', $updates)) {
                $updates['contact_name'] = ValidationService::validateString($updates['contact_name'], 63, true);
                if ($updates['contact_name'] === false) {
                    throw new ValidationException('Ime nije odgovarajućeg formata', 1019);
                }

                $address->contact_name = $updates['contact_name'];
            }

            if (array_key_exists('contact_surname', $updates)) {
                $updates['contact_surname'] = ValidationService::validateString($updates['contact_surname'], 63, true);
                if ($updates['contact_surname'] === false) {
                    throw new ValidationException('Prezime nije odgovarajućeg formata', 1020);
                }

                $address->contact_surname = $updates['contact_surname'];
            }

            if (array_key_exists('company', $updates)) {
                $updates['company'] = ValidationService::validateString($updates['company'], 63, true);
                if ($updates['company'] === false) {
                    throw new ValidationException('Naziv kompanije nije odgovarajućeg formata', 1029);
                }

                $address->company = $updates['company'];
            }

            if (array_key_exists('pib', $updates)) {
                $updates['pib'] = ValidationService::validateInteger($updates['pib']);
                if ($updates['pib'] === false) {
                    throw new ValidationException('Naziv kompanije nije odgovarajućeg formata', 1029);
                }

                $address->pib = $updates['pib'];
            }

            if (array_key_exists('address', $updates)) {
                $updates['address'] = ValidationService::validateString($updates['address'], 127, true);
                if ($updates['address'] === false) {
                    throw new ValidationException('Adresa nije odgovarajućeg formata', 1021);
                }

                $address->address = $updates['address'];
            }

            if (array_key_exists('postal_code', $updates)) {
                $updates['postal_code'] = ValidationService::validatePostalCode($updates['postal_code']);
                if ($updates['postal_code'] === false) {
                    throw new ValidationException('Poštanski broj nije odgovarajućeg formata', 1022);
                }

                $address->postal_code = $updates['postal_code'];
            }

            if (array_key_exists('phone_nr', $updates)) {
                $updates['phone_nr'] = ValidationService::validatePhoneNumber($updates['phone_nr']);
                if ($updates['phone_nr'] === false) {
                    throw new ValidationException('Telefonski broj nije odgovarajućeg formata', 1023);
                }

                $address->phone_nr = $updates['phone_nr'];
            }


            if (array_key_exists('email', $updates)) {
                if (ValidationService::validateEmail($updates['email'], 127) === false) {
                    throw new ValidationException('Email adresa nije odgovarajućeg formata', 1024);
                }

                $address->email = $updates['email'];
            }

            if (array_key_exists('fax', $updates)) {
                $updates['fax'] = ValidationService::validatePhoneNumber($updates['fax']);
                if ($updates['fax'] === false) {
                    throw new ValidationException('Fax nije odgovarajućeg formata', 1025);
                }

                $address->fax = $updates['fax'];
            }

            if (array_key_exists('open_hours', $updates)) {
                if (ValidationService::validateString($updates['open_hours'], 255) === false) {
                    throw new ValidationException('Radno vreme nije odgovarajućeg formata', 1026);
                }

                $address->open_hours = $updates['open_hours'];
            }

            if (!empty($updates)) {
                self::$entity_manager->persist($address);
                self::$entity_manager->flush();
            }
        } elseif (array_key_exists('preferred_address_delivery', $updates)) {
            if ($address->address_type !== 'shop') {
                $updates['preferred_address_delivery'] = ValidationService::validateBoolean(
                    $updates['preferred_address_delivery']
                );
                $address->preferred_address_delivery = $updates['preferred_address_delivery'];
                if ($preferred_delivery !== null && $preferred_delivery->id !== $address->id) {
                    $preferred_delivery->preferred_address_delivery = false;
                    self::$entity_manager->persist($preferred_delivery);
                }
                self::$entity_manager->persist($address);
                self::$entity_manager->flush();
            }
        } elseif (array_key_exists('preferred_address_billing', $updates)) {
            if ($address->address_type !== 'shop') {
                $updates['preferred_address_billing'] = ValidationService::validateBoolean(
                    $updates['preferred_address_billing']
                );

                if ($preferred_billing !== null && $preferred_billing->id !== $address->id) {
                    $preferred_billing->preferred_address_billing = false;
                    self::$entity_manager->persist($preferred_billing);
                }
                $address->preferred_address_billing = $updates['preferred_address_billing'];

                self::$entity_manager->persist($address);
                self::$entity_manager->flush();
            }
        } else {
            $address->status = $deleted;
            $city         = array_key_exists('city', $updates) ? $updates['city'] : $address->city;
            $contact_name = array_key_exists('contact_name', $updates)
                ? $updates['contact_name']
                : $address->contact_name
            ;
            $contact_surname = array_key_exists('contact_surname', $updates)
                ? $updates['contact_surname']
                : $address->contact_surname
            ;
            $address_n    = array_key_exists('address_n', $updates) ? $updates['address_n'] : $address->address;
            $phone_nr     = array_key_exists('phone_nr', $updates) ? $updates['phone_nr'] : $address->phone_nr;
            $company      = array_key_exists('company', $updates) ? $updates['company'] : $address->company;
            $postal_code  = array_key_exists('postal_code', $updates) ? $updates['postal_code'] : $address->postal_code;
            $pib          = array_key_exists('pib', $updates) ? $updates['pib'] : $address->pib;
            self::$entity_manager->persist($address);
            self::$entity_manager->flush();

            $create_address =  self::createAddressUser(
                $user_id,
                $city,
                $contact_name,
                $contact_surname,
                $address_n,
                $postal_code,
                $phone_nr,
                false,
                false,
                $company,
                $pib
            );


            return $create_address;
        }

        return true;
    }

    /**
     *
     * DELETE
     *
     */

    /**
     * Briše adresu
     * @param   int         $address_id     Id adrese
     * @return  bool        Vraća true ako je sve prošlo uredu
     */
    public static function deleteAddress($address_id) {
        $unused = 0;
        $used   = 1;
        $deleted = 2;

        $address = self::getAddressById($address_id);

        if (!empty($address)) {
            if ($address->address_type === 'user' &&
                PermissionService::checkPermission('user_update') === false &&
                $address->user->id !== UserService::getCurrentUserId()
            ) {
                throw new PermissionException('Nemate dozvolu za brisanje adrese korisnika', 1027);
            } elseif ($address->address_type === 'shop' &&
                PermissionService::checkPermission('shop_update') === false
            ) {
                throw new PermissionException('Nemate dozvolu za brisanje adrese radnje', 1028);
            }

            if ($address->address_type === 'shop' || $address->status === $unused) {
                self::$entity_manager->remove($address);
                self::$entity_manager->flush();
            } else {
                $address->status = $deleted;
                self::$entity_manager->persist($address);
                self::$entity_manager->flush();
            }
        }

        return true;
    }

    public static function deleteUserAddresses($user_id) {
        $addresses = self::getAddressesByUserId($user_id);

        foreach ($addresses as $address) {
            self::$entity_manager->remove($address);
            self::$entity_manager->flush();
        }

        return true;
    }

    // public static function isShopAddressSelected($address_id) {
        
    // return  self::$entity_manager->createQueryBuilder()
    //         ->select('a')
    //         ->from('App\Models\Addresses\Address', 'a')
    //         ->where('a.id = ?1')
    //         ->andWhere('a INSTANCE OF :discr')
    //         ->setParameter('1', $address_id)
    //         ->setParameter('discr', 'shop')
    //         ->setMaxResults(1)
    //         ->getQuery()->getOneOrNullResult()
    //     ;
    // }

    public static function isAddressDuplicate($user_id, $address_id, $address) {
        $addresses = self::getAddressesByUserId($user_id);
        foreach ($addresses as $a) {
            if ($a->id !== $address_id && $a->address === $address) {
                return true;
            }
        }
        return false;
    }

    // public static function isAddressDuplicateEdit($user_id, $address_id, $address) {
    //     $addresses = self::getAddressesByUserId($user_id);
    //     foreach ($addresses as $a) {
    //         if ($a->id !== $address_id && $a->address === $address) {
    //             return true;
    //         }
    //     }
    //     return false;
    // }

    public static function isPhoneDuplicate($user_id, $address_id, $phone) {
        $addresses = self::getAddressesByUserId($user_id);
        foreach ($addresses as $a) {
            if ($a->id !== $address_id && $a->phone_nr === $phone) {
                return true;
            }
        }
        return false;
    }

    // public static function isPhoneDuplicateEdit($user_id, $address_id, $phone) {
    //     $addresses = self::getAddressesByUserId($user_id);
    //     foreach ($addresses as $a) {
    //         if ($a->id !== $address_id && $a->phone_nr === $phone) {
    //             return true;
    //         }
    //     }
    //     return false;
    // }
}
