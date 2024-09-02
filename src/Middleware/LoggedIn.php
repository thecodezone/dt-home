<?php

namespace DT\Home\Middleware;

use DT\Home\Illuminate\Http\RedirectResponse;
use DT\Home\Psr\Http\Message\ResponseInterface;
use DT\Home\Psr\Http\Message\ServerRequestInterface;
use DT\Home\Psr\Http\Server\MiddlewareInterface;
use DT\Home\Psr\Http\Server\RequestHandlerInterface;
use function DT\Home\redirect;
use function DT\Home\route_url;

class LoggedIn implements MiddlewareInterface
{
	public function process( ServerRequestInterface $request, RequestHandlerInterface $handler ): ResponseInterface
    {

        $require_login = get_option( 'dt_home_require_login', true );

        if ( !is_user_logged_in() && ( $require_login == " " || $require_login == 1 ) ) {
            return redirect( route_url( "/login" ) );
        }

        return $handler->handle( $request );
    }
}
