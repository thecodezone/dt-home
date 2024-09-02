<?php

namespace DT\Home\Middleware;

use DT\Home\CodeZone\Router\Middleware\Middleware;
use DT\Home\Illuminate\Http\Request;
use DT\Home\Symfony\Component\HttpFoundation\Response;

class UnCached implements Middleware {

    protected $value;

    public function handle( Request $request, Response $response, callable $next )
    {
        $response->headers->set( 'Cache-Control', 'uncached' );

        return $next( $request, $response );
    }
}
