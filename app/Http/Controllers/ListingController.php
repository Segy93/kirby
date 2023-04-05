<?php

namespace App\Http\Controllers;

use App\Components\ProductListItSvet;
use App\Providers\ConfigService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 *
 * Listinzi, IT Svet cenovnig za sada
 *
 */
class ListingController extends BaseController {
    /**
     * it svet stranica, generise listing proizvoda
     *
     * @param   Request     $request        HTTP request ka strani
     * @return  string                      Generisani HTML strane
     */
    public function itSvet(Request $request): Response {
        $token = $request->query('token');
        $is_valid = is_string($token) && ConfigService::validateTokenItSvet($token);
        header('Content-Type: text/plain');

        if ($is_valid) {
            $content = (new ProductListItSvet())->renderHTML();
            $status = 200;
            $type = 'text/plain';
            return (new Response($content, $status))->header('Content-Type', $type);
        } else {
            redirect()->route('index');
        }
    }
}
