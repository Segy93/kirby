<?php

namespace App\Providers;

use App\Providers\BaseService;

class SessionService extends BaseService {


    /**
     * Kreira ključ u sesiji servisa
     * @param   string      $service        Naziv servisa
     * @return  void
     */
    protected static function createSessionKeyForService($service = null) {
        $service = empty($service) ? self::getFromService(debug_backtrace()) : $service;

        if (self::getSessionKeyExists($service) !== true) {
            $_SESSION[$service] = [];
        }
    }

    public static function getCsrfMeta() {
        return '<meta name="csrf-token" id = "csrf-token" content="' . $_SESSION['token'] . '">';
    }

    public static function getCsrfField() {
        return '<input name="csrf-token" type="hidden" value="' . $_SESSION['token'] . '"/>';
    }

    /**
     * Dohvata i vraća naziv servisa odakle je funkcija pozvana
     * @param  debug_backtrace()        $trace      Niz sa parametrima odakle se poziva funkcija
     * @return string                   $service    Naziv servisa odkle je funkcija pozvana
     */
    private static function getFromService($trace) {
        $fileName = $trace[0]['file'];
        $service = substr($fileName, strrpos($fileName, '/') + 1);

        return strstr($service, '.', true);
    }

    /**
     * Proverava dali postoji vrednost u nizu podključa ključa servisa
     * @param   string      $service        Naziv servisa
     * @param   string      $key            Naziv ključa
     * @param   string/int  $value          Vrednost
     * @return  bool        True ako postoji u suprotnom false
     */
    private static function getValueExistForSessionKeyForService($service, $key, $value) {
        return self::getSessionKeyExistsForService($service, $key)
            ? in_array($value, $_SESSION[$service][$key])
            : false
        ;
    }

    /**
     * Dali postoji u sesiji ključ za taj servis
     * @param  string       $service        Naziv servisa
     * @return bool         Vraća true ako postoji u suprotnom vraća false
     */
    private static function getSessionKeyExists($service) {
        return array_key_exists($service, $_SESSION) ? true : false;
    }

    /**
     * Dali postoji podključ u ključu servisa u sesiji
     * @param  string       $service        Naziv servisa
     * @param  string       $key            Naziv ključa u servisu
     * @return bool         Vraća true ako postoji u suprotnom vraća false
     */
    private static function getSessionKeyExistsForService($service, $key) {
        return self::getSessionKeyExists($service) ? array_key_exists($key, $_SESSION[$service]) : false;
    }

    public static function getSessionValueForService($key, $service) {
        return self::getSessionKeySubKeyValueForService($key, $service);
    }

    /**
     * Dohvata vrednost podključa ključa servisa
     * @param   string          $key            Naziv ključa
     * @param   string          $service        Naziv servisa(opcionalno)
     * @return  array|string    String ili niz vrednosti
     */
    protected static function getSessionKeySubKeyValueForService($key, $service) {
        //var_dump($service);die;
        //$service = empty($service) ? self::getFromService(debug_backtrace()) : $service;

        return self::getSessionKeyExistsForService($service, $key) ? $_SESSION[$service][$key] : null;
    }



    public static function setSessionForService($key, $value, $is_array = false, $service = null) {
        return self::setSessionKey($key, $value, $is_array, $service);
    }

    /**
     * Postavljla vrednost ključa
     * @param   string      $key        Nazic ključa
     * @param   string      $value      Vrednost
     * @param   bool        $array      Ako je niz dodaje vrednost u nizu
     *                                  u suprontom postavlja samo vrednost za taj ključ
    */
    protected static function setSessionKey($key, $value, $is_array = false, $service = null) {
        if (self::getSessionKeyExists($service) !== true) {
            self::createSessionKeyForService($service);
        }

        if ($is_array) {
            if (self::getValueExistForSessionKeyForService($service, $key, $value) !== true) {
                $_SESSION[$service][$key][] = $value;
            }
        } else {
            $_SESSION[$service][$key] = $value;
        }
    }

    /**
     *
     * UPDATE
     *
     */

    /**
     * Menja vrednost podključa u podključu
     * @param   string  $key        Naziv podključa
     * @param   string  $sub_key    Naziv ključa u podključu
     * @param   mixed   $sub_value  Vrednosts
     * @return  void
     */
    protected static function updateValueOfSubkeyOfSubkeyForService($key, $sub_key, $sub_value, $service) {
        if (!empty(self::getSessionKeyExistsForService($service, $key))) {
            $_SESSION[$service][$key][$sub_key] = $sub_value;
        }
    }


    /**
     *
     * DELETE
     *
     */

    /**
     * Briše podključ iz servis ključa
     * @param   string      $key            Naziv ključa
     * @param   string      $service        Naziv servisa(opcionalno)
     * @return  void
     */
    protected static function deleteSessionSubkeyForService($key, $service) {
        if (self::getSessionKeyExistsForService($service, $key)) {
            unset($_SESSION[$service][$key]);
        }
    }

    /**
     * Nisam mogao da pristupim funkcijama zato sto su zasticene pa da ne bih brljao po kodu
     * napravio sam privremeno resenje.
     */

    public static function deleteSession($key, $service) {
        return self::deleteSessionSubkeyForService($key, $service);
    }
    /**
     * Briše podključ u podključu
     * @param   string  $key        Podključ
     * @param   string  $sub_key    Ključ u podključu
     * @return  void
     */
    protected static function deleteSessionSubkeyOfSubkeyForService($key, $sub_key, $service) {
        unset($_SESSION[$service][$key][$sub_key]);
    }

    /**
     * Briše vrodnost iz podniza
     * @param   string      $sub_key        Naziv podključa
     * @param   string      $value          Vrednost koja se nalazi u nizu podključa
     * @param   string      $service        Naziv servis(opcionalni parametar)
     * @return  void
     */
    protected static function removeValueFromSessionSubkeyForService($sub_key, $value, $service) {
        $value_key = array_search($value, $_SESSION[$service][$sub_key]);

        if ($value_key !== false) {
            unset($_SESSION[$service][$sub_key][$value_key]);
        }
    }
}
