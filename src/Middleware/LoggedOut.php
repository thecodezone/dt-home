<?php

namespace DT\Home\Middleware;

use DT\Home\CodeZone\Router\Middleware\Middleware;
use DT\Home\Illuminate\Http\RedirectResponse;
use DT\Home\Illuminate\Http\Request;
use DT\Home\Plugin;
use DT\Home\Symfony\Component\HttpFoundation\Response;
use function DT\Home\route_url;

class LoggedOut implements Middleware
{

    public function handle( Request $request, Response $response, $next )
    {

        if ( is_user_logged_in() ) {

            $response = new RedirectResponse( route_url(), 302 );

        }

        return $next( $request, $response );
    }
}
