<?php

namespace App\Providers;

use App\Providers\UserService;
use App\Providers\ProductService;
use App\Models\WishList;
use App\Exceptions\PermissionException;
use App\Exceptions\ValidationException;

class WishListService extends BaseService {
    protected static $service = 'WishListService';

    /**
     *
     * CREATE
     *
     */

    /**
     * Dodaje proizvod i wishList
     * @param   int         $product_id     Id proizvoda
     * @param   int         $user_id        Id korisnika
     * @return  bool/int    Vraća true ako je sve prošlo uredu u suprotnom vraća error code
     */
    public static function addToList($product_id, $user_id = null) {
        //Dohvata proizvod
        $product = ProductService::getProductById($product_id);
        if (empty($product)) {
            throw new ValidationException('Proizvod sa tim id-om nije pronađen', 24001);
        }

        if (empty($user_id)) {
            $user_id = UserService::getCurrentUserId();
        }

        if ($user_id !== false) {
            //Dohvata korisnika
            $user = UserService::getUserById($user_id);

            //Inicijalizacija modela
            $wishList = new WishList();

            //Setujem propertije
            $wishList->product  =   $product;
            $wishList->user     =   $user;

            //Čuvanje u bazi
            self::$entity_manager->persist($wishList);
            self::$entity_manager->flush();
        } else {
            $product_wishlist = [
                'product_id'    =>  $product_id,
            ];

            $wishlist = self::getSessionKeySubKeyValue('wishlist');

            if (empty($wishlist)) {
                self::setSession('wishlist', $product_wishlist, true);
            } else {
                $product_array_key = array_search($product_id, array_column($wishlist, 'product_id'));

                if ($product_array_key !== false) {
                    self::updateValueOfSubkeyOfSubkey('wishlist', $product_array_key, $product_wishlist);
                } else {
                    self::setSession('wishlist', $product_wishlist, true);
                }
            }
        }

        return true;
    }



    /**
     *
     * READ
     *
     */




    public static function getWishListCurrent() {
        $user_id = UserService::getCurrentUserId();
        return self::getWishListByUserId($user_id);
    }
    /**
     * Dohvata wishList po id-u
     * @param   int         $wishlist_id        Id wishlist-a
     * @return  WishList    Vraća objekat WishList
     */
    public static function getWishListById($wishlist_id) {
        return self::$entity_manager->find('App\Models\WishList', $wishlist_id);
    }

    /**
     * Dohvata wishList po id-u proizvoda i id-u korisnika
     * @param   int         $product_id     Id proizvoda
     * @param   int         $user_id        Id korisnika
     * @return  WishList    Vraća objekat ako je pronašao objekat u suprotnom vraća null
     */
    public static function getWishListByProductIdUserId($product_id, $user_id) {
        if (empty($user_id)) {
            $user_id = UserService::getCurrentUserId();
        }

        $wishlist_objects = [];

        $qb = self::$entity_manager->createQueryBuilder();
        if ($user_id !== false) {
            $wishlist_objects = $qb
                ->select('wl')
                ->from('App\Models\WishList', 'wl')
                ->where('wl.product_id = ?1')
                ->setParameter(1, $product_id)
                ->andWhere('wl.user_id = ?2')
                ->setParameter(2, $user_id)
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult()
            ;
        } else {
            $wishlist = self::getSessionKeySubKeyValue('wishlist');

            if (!empty($wishlist)) {
                foreach ($wishlist as $key => $item) {
                    if ($item['product_id'] === $product_id) {
                        $product = ProductService::getProductById($item['product_id']);
                        $wishlist_object = new WishList();

                        $wishlist_object->id            = $key;
                        $wishlist_object->product       = $product;
                        $wishlist_object->product_id    = $product->id;
                        $wishlist_object->user_id       = null;

                        $wishlist_objects[] = $wishlist_object;
                    }
                }
            }
        }

        return $wishlist_objects;
    }

    /**
     * Dohvata listu želja za korisnika
     * @param   int      $user_id        Id korisnika
     * @return  array    Listu elemenata iz liste želja
     */
    public static function getWishListByUserId($user_id = null) {
        if (empty($user_id)) {
            $user_id = UserService::getCurrentUserId();
        }

        $wishlist_objects = [];

        $current_user = UserService::getCurrentUserId();
        if ($user_id !== false) {
            if (PermissionService::checkPermission('user_read') === false
                && $current_user !== $user_id
            ) {
                throw new PermissionException('Nemate dozvolu za dohvatanje ove liste želja', 1016);
            }

            $qb = self::$entity_manager->createQueryBuilder();

            $wishlist_objects = $qb
                ->select('wl, p')
                ->from('App\Models\WishList', 'wl')
                ->join('wl.product', 'p')
                ->where('wl.user_id = :user_id')
                ->setParameter('user_id', $user_id)
                ->getQuery()
                ->getResult()
            ;
        } else {
            $wishlist = self::getSessionKeySubKeyValue('wishlist');

            if (!empty($wishlist)) {
                foreach ($wishlist as $key => $item) {
                    $product = ProductService::getProductById($item['product_id']);
                    $wishlist_object = new WishList();

                    $wishlist_object->id            = $key;
                    $wishlist_object->product       = $product;
                    $wishlist_object->product_id    = $product->id;
                    $wishlist_object->user_id       = null;

                    $wishlist_objects[] = $wishlist_object;
                }
            }
        }

        return $wishlist_objects;
    }

    public static function changeWishList($product_id, $user_id = null) {
        if (empty($user_id)) {
            $user_id = UserService::getCurrentUserId();
        }

        if ($user_id !== false) {
            if (UserService::getCurrentUserId() !== $user_id) {
                throw new PermissionException('Nemate dozvolu za izmenu korpe', 19030);
            }

            $qb = self::$entity_manager->createQueryBuilder();

            $wishlist = $qb
                ->select('c')
                ->from('App\Models\Cart', 'c')
                ->where('c.user_id = :user_id')
                ->setParameter('user_id', $user_id)
                ->andWhere('c.product_id = :product_id')
                ->setParameter('product_id', $product_id)
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult()
            ;
        } else {
            $product_wishlist = [
                'product_id'    =>  $product_id,
            ];

            $wishlist = self::getSessionKeySubKeyValue('wishlist');

            if (empty($wishlist)) {
                self::setSession('wishlist', $product_wishlist, true);
            } else {
                $product_array_key = array_search($product_id, array_column($wishlist, 'product_id'));

                if ($product_array_key !== false) {
                    self::updateValueOfSubkeyOfSubkey('wishlist', $product_array_key, $product_wishlist);
                } else {
                    self::setSession('wishlist', $product_wishlist, true);
                }
            }
        }

        return true;
    }

    /**
     *
     * DELETE
     *
     */

    /**
     * Briše element iz wishList po id-u
     * @param   int         $wishlist_id    Id liste
     * @return  bool/int    Vraća true ako je sve prošlo uredu u suprotnom vraća error_code
     */
    public static function deleteFromList($wishlist_id) {
        //Dohvata wishlist objekat
        if (UserService::getCurrentUserId() !== false) {
            $wishList = self::getWishListById($wishlist_id);
            if (empty($wishList)) {
                throw new ValidationException('Element u listi sa tim id-om nije pronađen', 1);
            }

            //Briše WishList objekat
            self::$entity_manager->remove($wishList);
            self::$entity_manager->flush();
        } else {
            self::deleteSessionSubkeyOfSubkey('wishlist', $wishlist_id);
        }

        return true;
    }


    /**
     * Briše iz liste
     * @param  int          $product_id     Id proizvoda
     * @param  int          $user_id        Id korisnika
     * @return bool/int     Vraća true ako je sve prošlo uredu u suprotno vraća neki error_code
     */
    public static function deleteFromListByProductIdUserId($product_id, $user_id) {
        $wishList = self::getWishListByProductIdUserId($product_id, $user_id);

        if (empty($wishList)) {
            throw new ValidationException('Element u listi nije pronađen', 1);
        }

        if (UserService::getCurrentUserId() === false) {
            foreach ($wishList as $row) {
                self::deleteSessionSubkeyOfSubkey('wishlist', $row->id);
            }
        } else {
            self::$entity_manager->remove($wishList);
            self::$entity_manager->flush();
        }

        return true;
    }
}
