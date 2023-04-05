<?php

namespace App\Providers;

use App\Providers\SessionService;
use Doctrine\ORM\EntityManager;
use Illuminate\Support\Facades\Log;

/**
 * Matični servis
 */
class BaseService {
    /**
     * Doctrine entity manager
     *
     * @var EntityManager
     */
    protected static $entity_manager;

    protected static $session;

    protected static $static_folder = 'uploads_static';

    protected static $static_originals = 'uploads_static/originals';

    protected static $pictures_originals = '../../photos/';

    protected static $queued_entities = [];

    protected static $service = 'BaseService';


    /**
     *
     * CREATE
     *
     */

    public static function setEntityManager(EntityManager $em) {
        self::$entity_manager = $em;
    }


    protected static function setSession($key, $value, $is_array = false) {
        return SessionService::setSessionForService($key, $value, $is_array, static::$service);
    }

    /**
     *
     * READ
     *
     */


    protected static function getSessionKeySubKeyValue($value) {
        return SessionService::getSessionValueForService($value, static::$service);
    }

    /**
     * Vraća početnu putanju
     * @return  string      Putanja korena public foldera
     */
    protected static function getPath() {
        $path = $_SERVER['DOCUMENT_ROOT'];
        if (substr($path, -1) === '/') {
            $path = rtrim($path, '/');
        }

        return $path;
    }

    protected static function getUploadsPath() {
        $root = self::getPath();
        return $root . '/uploads';
    }

    /**
     * Dohvata ip adresu korisnika
     * @return  string      Ip adresa korisnika
     */
    public static function getUserIpAddress() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        } else {
            return '';
        }
    }

    public static function getProtocol() {
        return isset($_SERVER['HTTPS']) ? "https" : "http";
    }

    /**
     *
     * @param   int     $country_id     Id države
     * @return  array   Vraća niz gradova
     */
    public static function getCitiesByCountryId($country_id) {
        try {
            $qb = self::$entity_manager->createQueryBuilder();

            return $qb
                ->select('c')
                ->from('App\Models\City', 'c')
                ->where('c.country_id = :country_id')
                ->setParameter('country_id', $country_id)
                ->getQuery()
                ->getResult()
            ;
        } catch (\Doctrine\DBAL\DBALException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Dohvata zemlju po id-u
     * @param       int         $country_id        Id države
     * @return      Countru     Vraća modela države
     */
    public static function getCountryById($country_id) {
        return self::$entity_manager->find('App\Models\Country', $country_id);
    }

    public static function checkCSRFToken() {
        $ip      = self::getUserIpAddress();
        $headers = getallheaders();
        $sent_token = '';
        if (array_key_exists('X-CSRF-TOKEN', $headers)) {
            $sent_token = $headers['X-CSRF-TOKEN'];
        } elseif (array_key_exists('X-Csrf-Token', $headers)) {
            $sent_token = $headers['X-Csrf-Token'];
        } elseif (array_key_exists('X-CSRF-TOKEN', $_POST)) {
            $sent_token = $_POST['X-CSRF-TOKEN'];
        } elseif (array_key_exists('X-Csrf-Token', $_POST)) {
            $sent_token = $_POST['X-Csrf-Token'];
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($sent_token === '') {
                if (array_key_exists('csrf-token', $_POST)) {
                    $sent_token = $_POST['csrf-token'];
                }
            }
            if ($sent_token !== $_SESSION['token']) {
                Log::info('No token execution from ip ' . $ip);
                die;
            }
        }
    }

    /**
     *
     * UPDATE
     *
     */



    protected static function updateValueOfSubkeyOfSubkey($key, $sub_key, $sub_value) {
        return SessionService::updateValueOfSubkeyOfSubkeyForService($key, $sub_key, $sub_value, static::$service);
    }





    /**
     *
     * DELETE
     *
     */




    protected static function deleteSessionSubkeyOfSubkey($key, $sub_key) {
        return SessionService::deleteSessionSubkeyOfSubkeyForService($key, $sub_key, static::$service);
    }

    protected static function removeValueFromSessionSubkey($key, $value) {
        return SessionService::removeValueFromSessionSubkeyForService($key, $value, static::$service);
    }

    protected static function deleteSessionSubkey($key) {
        return SessionService::deleteSessionSubkeyForService($key, static::$service);
    }
}
