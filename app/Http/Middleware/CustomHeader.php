<?php

namespace App\Http\Middleware;

use Closure;

use App\Providers\AdminService;
use Illuminate\Http\Request;

class CustomHeader {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        $response   = $next($request);
        $token      = $_SESSION['token'];
        $admin      = AdminService::isAdminLoggedIn();

        if (config(php_uname('n') . '.SEND_HEADERS')) {
            if (!$admin) {
                $response->header('Content-Security-Policy', "default-src 'none' ;manifest-src 'self' ; script-src  'self' 'unsafe-eval' 'nonce-" . $token . "' https://www.google.com/recaptcha/ ; style-src 'self' 'nonce-" . $token . "'   https://fonts.googleapis.com; img-src 'self' ; font-src 'self' https://fonts.gstatic.com data:; connect-src 'self' ; media-src 'none' ; object-src 'none' ; child-src 'none' ; frame-src 'self' https://www.google.com/ https://www.youtube.com ; worker-src 'self' ; frame-ancestors 'none' ; form-action 'self' ; block-all-mixed-content; base-uri 'self';");
            }
            $response->header('X-Frame-Options', 'SAMEORIGIN');
            $response->header('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
            $response->header('X-XSS-Protection', '1; mode=block');
            $response->header('X-Content-Type-Options', 'nosniff');
            $response->header('Referrer-Policy', 'no-referrer');
        }

        return $response;
    }
}
