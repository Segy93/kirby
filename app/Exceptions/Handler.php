<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
// Usinge koje sam ja dodao
use \Doctrine\DBAL\DBALException as DBE;
use App\Exceptions\PermissionException as PE;
use App\Exceptions\ValidationException as VE;
use App\Providers\BaseService;
use Illuminate\Support\Facades\Log;
use Throwable;

class Handler extends ExceptionHandler {
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  Throwable  $e
     * @return void
     */
    public function report(Throwable $e) {
        $ip_address =   BaseService::getUserIpAddress();
        $code       =   $e->getCode();
        $message    =   $e->getMessage();
        if ($e instanceof PE) {
            Log::info("Ip address: $ip_address \n Pristup bez dozvole: $message");
        } elseif ($e instanceof VE) {
            Log::info("Ip address: $ip_address \n Pokušaj unošenja ne ispravnih podataka:  $message");
        } elseif ($e instanceof DBE) {
            Log::error("Ip address: $ip_address \n Greška u bazi: $message");
        } elseif ($e instanceof \Exception) {
            Log::error("Ip address: $ip_address \n Nepoznata greška: $message \n $code");
        } elseif ($e instanceof \Error) {
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

            Log::critical("Ip address: $ip_address \n Fatalna greška: " . $message);
        }

        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Throwable  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $e) {
        if ($e instanceof NotFoundHttpException) {
            return redirect()->route('notFound');
        }
        return parent::render($request, $e);
    }
}
