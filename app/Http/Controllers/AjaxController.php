<?php

namespace App\Http\Controllers;

use Log;
use Illuminate\Http\Request;
use App\Providers\BaseService;
use App\Providers\PermissionService;
use App\Exceptions\PermissionException;
use App\Exceptions\ValidationException;

class AjaxController extends BaseController {

    public function handleRequestRegular(Request $request) {
        $requests = $request->input('queue');
        $batch = intval($request->input('batch'));
        $resp = [];

        foreach ($requests as $single) {
            $name = 'App\Components\\' . $single['name'];
            $meth = $single['component_method'];
            $params = empty($single['params']) ? [] : $single['params'];

            $code   = 200;
            $data   = null;
            $error  = null;
            $ok     = true;

            try {
                $data = (new $name())->$meth($params);
            } catch (PermissionException $e) {
                $error = $e->reportAjax();
            } catch (ValidationException $e) {
                $error = $e->reportAjax();
            } catch (\Doctrine\DBAL\DBALException $e) {
                $error = [
                    'error'     =>  $e->getCode(),
                    'message'   =>  $e->getMessage(),
                ];
            } catch (\Exception $e) {
                $error = [
                    'code'      =>  $e->getCode(),
                    'message'   =>  $e->getMessage(),
                ];
            } catch (\Error $e) {
                $message    =   $e->getMessage();
                $code       =   $e->getCode();

                if (strpos($message, 'undefined method') !== false) {
                    $error = [
                        'code'      =>   100,
                        'message'   =>   'Metoda ne postoji',
                    ];
                } elseif (strpos($message, 'function toArray()') !== false) {
                    $error = [
                        'code'      =>  101,
                        'mesage'    =>  'Nemožete pozivati toArray metodu na nizu',
                    ];
                } else {
                    $error = [
                        'code'      =>  $code,
                        'message'   =>  $message,
                    ];
                }
            } finally {
                $code   = $error !== null ? 400     :   200;
                $ok     = $error !== null ? false   :   true;

                $resp[] = [
                    'code'      => $code,
                    'ok'        => $ok,
                    'error'     => $error,
                    'data'      => $data,
                    'key'       => $single['name'] . '--' . $meth,
                    'timestamp' => $single['timestamp'],
                ];
            }
        }

        return [
            'batch' => $batch,
            'data'  => $resp,
        ];
    }

    public function handleRequestRaw(Request $request) {
        $params = $request->all();

        $name = 'App\Components\\' . $params['component_name'];
        $meth = $params['component_method'];

        unset($params['component_name']);
        unset($params['component_method']);

        return json_encode((new $name())->$meth($params), true);
    }
}
