<?php

namespace App\Components;

use App\Components\BaseComponent;
use App\Providers\ImportService;

/**
 * Služi za import podataka iz među-baze monitor-a u bazu aplikacije
 */
class DataImportOld extends BaseComponent {

    private static $action;

    public function __construct($action) {
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
        $print_immediate = function ($print) {
            if (is_array($print)) {
                foreach ($print as $p) {
                    echo $p;
                    echo PHP_EOL;
                }
            } else {
                echo $print;
                echo PHP_EOL;
            }
            $this->writeBufferFlush();
        };

        $action = self::$action;

        if (config(php_uname('n') . '.PROGRESS_SHOW_HTML')) {
            return view('DataImport/templates/DataImport', [
                'data'              =>  ImportService::$action($param),
                'print_immediate'   =>  $print_immediate,
            ]);
        } else {
            foreach (ImportService::$action($param) as $key => $value) {
                $json_report[$key] = $value;
            }
            return $json_report;
        }
    }

    private function writeBufferFlush() {
        echo(str_repeat(' ', 4000000));
        if (@ob_get_contents()) {
            @ob_end_flush();
        }
        flush();
    }
}
