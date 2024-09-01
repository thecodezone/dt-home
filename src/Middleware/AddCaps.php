<?php

namespace DT\Home\Middleware;

use DT\Home\CodeZone\Router\Middleware\Middleware;
use DT\Home\Illuminate\Http\Request;
use DT\Home\Symfony\Component\HttpFoundation\Response;
use DT_Magic_Url_Base;
use function DT\Home\container;

/**
 * Check if the current path is a magic link path.
 */
class AddCaps implements Middleware {
    public function handle( Request $request, Response $response, callable $next ) {
        add_filter( 'user_has_cap', [ $this, 'user_has_cap' ], 10, 3 );
    }
}
