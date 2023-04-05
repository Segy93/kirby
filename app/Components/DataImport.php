<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\ImportService;

/**
 * Služi za import podataka iz među-baze monitor-a u bazu aplikacije
 */
class DataImport extends BaseComponent {

    private static $action;

    public function __construct($action) {
        header('Content-Type: text/html');
        header('Cache-Control: no-cache');

        try {
            $actions = [
                'importProducts',
                'importProduct',
                'updateProducts',
                'updateProduct',
            ];

            if (!in_array($action, $actions)) {
                throw new \Exception("Akcija $action ne postoji", 1);
            }

            self::$action = $action;
        } catch (\Exception $e) {
            echo $e->getMessage();
            die;
        }
    }

    public function import($param = null) {
        $action =  self::$action;
        $json_report = [];

        foreach (ImportService::$action($param, [], [], true) as $key => $value) {
            if (config(php_uname('n') . '.PROGRESS_SHOW_HTML')) {
                echo $value;
            } else {
                $json_report[$key] = $value;
            }
        }

        if (config(php_uname('n') . '.PROGRESS_SHOW_HTML') === false) {
            return $json_report;
        }
    }
}
