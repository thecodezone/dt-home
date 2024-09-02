<?php

namespace DT\Home\Middleware;

use DT\Home\Psr\Http\Message\ResponseInterface;
use DT\Home\Psr\Http\Message\ServerRequestInterface;
use DT\Home\Psr\Http\Server\MiddlewareInterface;
use DT\Home\Psr\Http\Server\RequestHandlerInterface;
use function DT\Home\route_url;
use function DT\Home\redirect;

class LoggedOut implements MiddlewareInterface
{

	public function process( ServerRequestInterface $request, RequestHandlerInterface $handler ): ResponseInterface
    {

        if ( is_user_logged_in() ) {

            return redirect( route_url() );

        }

        return $handler->handle( $request );
    }
}
