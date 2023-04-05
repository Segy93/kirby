<?php

namespace App\Providers;

use Exception;
use Monolog\Logger;

/**
 *
 * Konfiguracija, bez obzira da li su lokalna podesavanja (tipa datetime stinrg format),
 * config fajlovi ili nesto trece
 *
 */
class ConfigService {
    /**
     * Dohvata ime fajla u kome je upisana konfiguracija
     *
     * @return  string                      Ime fajla
     */
    private static function getConfigFilename(): string {
        return php_uname('n');
    }

    /**
     * Koliko slika ce najvise biti prikazano u okviru slajdera na vrhu strane
     *
     * @return integer                      Maksimalni broj slika
     */
    public static function getBannerSliderMaxSlides(): int {
        return 7;
    }

    /**
     * Vraca formatiran datum
     *
     * @return string
     */
    public static function getDateStringFormat(): string {
        return 'Y-m-d';
    }

    /**
     * Da li treba usporiti uvoz proizvoda
     * (pomaze u debagovanju)
     *
     * @return boolean                      Da li usporiti
     */
    public static function getImportProgressThrottle(): bool {
        return config(self::getConfigFilename() . '.PROGRESS_SLOW_DOWN', false);
    }

    /**
     * Dohvata token za uvoz, sluzi za proveru dozvole korisnika da uvozi proizvode
     *
     * @return string
     */
    public static function getImportToken(): string {
        return config(self::getConfigFilename() . '.IMPORT_TOKEN');
    }

    /**
     * Da li da prilikom uvoza obrise postojece proizvode
     *
     * @return boolean                      Da li da brise
     */
    public static function getImportTruncateDatabase(): bool {
        $is_debug = env('APP_DEBUG', false);
        return $is_debug && config(self::getConfigFilename() . '.DATABASE_TRUNCATE', false);
    }

    /**
     * Nivo logovanja (u produkciji nema potrebe čuvati isti nivo logova kao u razvoju)
     *
     * @return  integer                     Nivo logovanja
     */
    public static function getLoggerLevel(): int {
        return config(self::getConfigFilename() . '.LOG_LEVEL', Logger::INFO);
    }

    /**
     * Da li treba upisivati u log tok uvoza
     *
     * @return boolean
     */
    public static function getShouldLogImport(): bool {
        $key = self::getConfigFilename() . '.IMPORT_LOGGING';
        $default = false;
        return config($key, $default);
    }

    /**
     * Dohvata osnovnu putanju do sajta, https://www.monitor.rs deo
     *
     * @return  string                      Pomenuta putanja
     */
    public static function getBaseUrl(): string {
        $protocol = isset($_SERVER['HTTPS']) ? 'https' : 'http';
        return $protocol . '://' . $_SERVER['SERVER_NAME'] . '/';
    }

    /**
     * Access key za responsive filemanager,
     * neophodan da bismo ograničili pristup upload modalu
     *
     * @return string|null
     */
    public static function getFmKey(): ?string {
        return config(php_uname('n') . '.RESPONSIVE_FILEMANAGER');
    }

    /**
     * Dohvata mejl adresu koju korisnici mogu da koriste da kontaktiraju prodaju
     *
     * @return string
     */
    public static function getEmailContact(): string {
        return config(php_uname('n') . '.EMAIL_SALES');
    }

    /**
     * Dohvata site key za Google servise, koristi se za reCaptcha
     *
     * @return string
     */
    public static function getGoogleSiteKey(): string {
        return config(php_uname('n') . '.GOOGLE_SITE_KEY');
    }

    /**
     * Dohvata token koji IT svet koristi za autentikaciju
     *
     * @return  string                      Sam token
     */
    public static function getTokenItSvet(): string {
        return config(php_uname('n') . '.TOKEN_ITSVET', 'it-svet-token');
    }

    /**
     * Proverava da li je prosledjeni token za IT svet ispravan
     *
     * @param   string      $token          Token koji se proverava
     * @return  boolean                     Da li je validan
     */
    public static function validateTokenItSvet(string $token): bool {
        return $token === self::getTokenItSvet();
    }
}
