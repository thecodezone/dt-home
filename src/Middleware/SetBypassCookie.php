<?php

namespace DT\Home\Middleware;

use DT\Home\CodeZone\Router\Middleware\Middleware;
use DT\Home\Illuminate\Http\Request;
use DT\Home\Symfony\Component\HttpFoundation\Response;

class SetBypassCookie implements Middleware {

    protected $value;

    public function get_bypass_value() {
        return md5( session_id() . wp_get_session_token() . time() );
    }

    public function handle( Request $request, Response $response, callable $next )
    {
        setcookie( 'DT_HOME', $this->get_bypass_value(), time() + 600, '/' );

        return $next( $request, $response );
    }
}
