<?php

namespace App\Providers;

use App\Exceptions\ValidationException;
use EmailChecker\EmailChecker;
use Illuminate\Support\Facades\Log;

class ValidationService extends BaseService {

    //Raspon brojeva ceo integer
    public static $RANGE_INTEGER = ['min' => -2147483648, 'max' => 2147483647];

    //Raspon brojeva ceo integer pozitivni
    public static $RANGE_INTEGER_UNSIGNED = ['min' => 0, 'max' => 2147483647];

    //Raspon brojeva smallInteger pozitivni
    public static $RANGE_SMALLINTEGER_UNSIGNED = ['min' => 0, 'max' => 65535];

    /**
     * Validacija email-a
     * @param   string          $email          Email nad kojim se radi validacija
     * @param   int             $max_length Maksimalna dužina email-a
     * @return  string/bool     Vraća email ako je uredu inače vraća false
     */
    public static function validateEmail($email, $max_length = null) {
        $checker = new EmailChecker();
        return $checker->isValid($email) && ($max_length === null || strlen($email) <= $max_length);
    }

    /**
     * Provera da li je korisnik uneo ispravan captcha
     *
     * @param   string      $response       Heš koji je Captcha generisao
     * @return  boolean                     Da li je ispravno popunjeno
     */
    public static function validateRecaptcha(string $response): bool {
        $secret = config(php_uname('n') . '.GOOGLE_SECRET_KEY');

        $post_data = http_build_query([
            'secret' => $secret,
            'response' => $response,
            'remoteip' => $_SERVER['REMOTE_ADDR'],
        ]);

        $opts = ['http' => [
            'content' => $post_data,
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'method'  => 'POST',
        ]];

        $verify_url = 'https://www.google.com/recaptcha/api/siteverify';
        $context  = stream_context_create($opts);
        $response = file_get_contents($verify_url, false, $context);
        $result = json_decode($response);

        if ($result->success === false) {
            foreach ($result->{'error-codes'} as $error) {
                Log::error('Neuspešna reCaptcha validacija: "' . $error . '"');
            }
        }

        return $result->success;
    }

    /**
     * Validacija bool
     * @param   bool    $bool   Bool nad kojim se radi validacija
     * @return  bool    Vrača true ili false
     */
    public static function validateBoolean($bool) {
        return boolval($bool);
    }

    /**
     * Validacija string-a
     * @param   string          $string         String nad kojim se radi validacija
     * @param   int             $max_length Maksimalna družina string-a
     * @return  string/bool     Vraća string ako je sve uredu inače vraća false
     */
    public static function validateString($string, $max_length = null, $options = []) {
        $clean = filter_var($string, FILTER_SANITIZE_STRING);
        if (is_bool($options) && $options === true) {
            $clean = strip_tags($clean);
        } elseif (is_array($options) && array_key_exists('strip_html', $options) && $options['strip_html'] === true) {
            $clean = strip_tags($clean);
        }

        if (is_array($options)
            && array_key_exists('empty_check', $options)
            && $options['empty_check'] === true && empty($clean)
        ) {
            throw new ValidationException('Polje ne sme da bude prazno');
        }

        return (empty($max_length) || strlen($clean) <= $max_length)
            ? $clean
            : substr($clean, 0, $max_length)
        ;
    }

    /**
     * Validacija integer-a
     * @param   integer         $integer        Broj nad kojim se radi validacija
     * @param   integer         $min_value      Minimalna vrednost dozvoljena
     * @param   integer         $max_value      Maksimalna vrednost dozvoljena
     * @return  integer/bool    Vraća broj ako je uredu inače vraća false
     */
    public static function validateInteger($integer, $min_value = null, $max_value = null) {
        $clean = filter_var($integer, FILTER_VALIDATE_INT);

        return (
            $clean === false
            || (!empty($min_value) && $clean < $min_value)
            || (!empty($max_value) && $clean > $max_value)
        ) ? false : $clean;
    }

    /**
     * Služi za validaciju lozinke
     * @param    string   $password   Lozinka koja se proverava
     * @return   string   $return     Vraća lozinku ako je prošla validaciju inače vraća false
     */
    public static function validatePassword($password, $max_length = null) {
        if (strlen($password) < 6) {
            throw new ValidationException('Lozinka je prekratka', 23001);
        }

        if (!preg_match("/[0-9]+/", $password)) {
            throw new ValidationException('Lozinka ne sadrži nijedan broj', 23002);
        }

        if (!preg_match("/[a-z]+/", $password)) {
            throw new ValidationException('Lozinka ne sadrži nijedno malo slovo', 23003);
        }

        if (!preg_match("/[A-Z]+/", $password)) {
            throw new ValidationException('Lozinka ne sadrži nijedno veliko slovo', 23004);
        }

        if (!empty($max_length) && strlen($password) > $max_length) {
            throw new ValidationException('Lozinka je predugačka', 23005);
        }

        return $password;
    }

    /**
     * Služi za validaciju telefonskog broja
     * @param    string   $number   Telefonski broj
     * @return   string   $number   Vraća broj ako je prošao validaciju inače vraća false
     */
    public static function validatePhoneNumber($number) {
        $number = str_replace('/', '', $number);
        $number = str_replace('-', '', $number);
        $number = str_replace(' ', '', $number);
        $pattern = '/^[0-9]+$/';

        return preg_match($pattern, $number) ? $number : false;
    }

    /**
     * Validacija poštanskog broja
     * @param   string          $postal_code    Poštanski broj
     * @return  string/bool     Vraća poštanski broj ako je sve prošlo uredu u suprotnom vraća false
     */
    public static function validatePostalCode($postal_code) {
        $pattern = '/^[0-9]+$/';

        return preg_match($pattern, $postal_code) ? $postal_code : false;
    }

    /**
     * Služi za validaciju datuma
     * @param   date    $date   Datum na kojim radite validaciju
     * @return  date    Vraća datum ako je prošao validaciju ili false ako nije
     */
    public static function validateDate($date) {
        return $date instanceof \DateTime ? $date : $date;
    }

    /**
     * Validacija html-a
     * @param   string      $input              Html koji se validira
     * @param   boolean     $strip_all_html     Skida sve html tagove ako je true u suprotnom ne skida
     * @return  string      $input              Vraća validiran html
     */
    public static function validateHTML($input, $strip_all_html = true) {
        if ($strip_all_html === true) {
            return self::validateString($input);
        } else {
            return self::closeTags($input);
        }
    }

    /**
     * Validacija url-a
     *
     * @param   string      $url            Url koji se validira
     * @return  string      Vraća validiran url
     */
    public static function validateURL($url) {
        $url = str_replace('(', '', $url);
        $url = str_replace(')', '', $url);
        $url = str_replace('"', '', $url);
        return str_replace(' ', '-', $url);
    }

    /**
     * Zatvare sve tagove.
     * @param   string      $html   String sa otvorenim tagovima.
     * @return  string      $html   Vraća html sa zatvorenim tagovima.
     */
    public static function closeTags($html) {
        return $html;
    }
}
