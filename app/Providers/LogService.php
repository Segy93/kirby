<?php

namespace App\Providers;

use App\Providers\BaseService;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * Logovanje događaja
 * (podržano je generičko, import i konfigurator logovanje)
 */
class LogService extends BaseService {
    private static $LOG__DIR__CONFIGURATOR = 'configurator';
    private static $LOG__DIR__IMPORT = 'import';

    /** @var ?\Monolog\Logger */
    private static $logger = null;

    /**
     * Osnovna putanja za logovanje, na koju se nadovezuju,
     * kao podfolderi, za pojedinačne namene
     *
     * @return  string                      Pomenuta putanja
     */
    private static function getLogPathBase(): string {
        return __DIR__ . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . 'storage' . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR
        ;
    }

    /**
     * Dohvata putanju na kojoj ce biti sacuvan configurator log
     * (storage/logs/configurator/2021-10-15/)
     *
     * @return string                       Putanja do loga
     */
    private static function getLogPathConfigurator(): string {
        $date = date(ConfigService::getDateStringFormat());
        $path = self::$LOG__DIR__CONFIGURATOR . DIRECTORY_SEPARATOR . $date . '.log';

        return self::getLogPathBase() . $path;
    }

    /**
     * Dohvata putanju na kojoj ce biti sacuvan import log
     * (storage/logs/import/2020-10-15/)
     *
     * @return string                       Putanja do loga
     */
    private static function getLogPathImport(): string {
        $date = date(ConfigService::getDateStringFormat());
        $path = self::$LOG__DIR__IMPORT . DIRECTORY_SEPARATOR . $date . '.log';

        return self::getLogPathBase() . $path;
    }

    /**
     * Inicijalizacija biblioteke koja vezuje loger sa fajlom
     *
     * @param   string      $path           Putanja na kojoj će biti zapisivano
     * @return  StreamHandler               Loger koji će se koristiti
     */
    private static function initLogHandler(string $path): StreamHandler {
        return new StreamHandler($path, ConfigService::getLoggerLevel());
    }

    /**
     * Inicijalizacija logera u fajl, ukoliko to vec nije uradjeno
     *
     * @param   string      $name           Naziv logera
     * @param   string      $log_path       Putanja log fajla
     * @return  \Monolog\Logger
     */
    private static function initLogger(string $name, string $log_path): Logger {
        if (self::$logger === null) {
            $logger = new Logger($name);
            $handler = self::initLogHandler($log_path);
            $logger->setHandlers([ $handler ]);
            self::$logger = $logger;
        }

        return self::$logger;
    }

    /**
     * Inicijalizacija logera za konfigurator
     *
     * @return \Monolog\Logger
     */
    public static function initLoggerConfigurator(): Logger {
        $log_path = self::getLogPathConfigurator();
        return self::initLogger('Configurator', $log_path);
    }
}
