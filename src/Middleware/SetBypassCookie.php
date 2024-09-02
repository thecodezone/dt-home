<?php

namespace DT\Home\Middleware;

use DT\Home\CodeZone\Router\Middleware\Middleware;
use DT\Home\Illuminate\Http\Request;
use DT\Home\Psr\Http\Message\ResponseInterface;
use DT\Home\Psr\Http\Message\ServerRequestInterface;
use DT\Home\Psr\Http\Server\MiddlewareInterface;
use DT\Home\Psr\Http\Server\RequestHandlerInterface;
use DT\Home\Symfony\Component\HttpFoundation\Response;

class SetBypassCookie implements MiddlewareInterface {

    protected $value;

    public function get_bypass_value() {
        return md5( session_id() . wp_get_session_token() . time() );
    }

	public function process( ServerRequestInterface $request, RequestHandlerInterface $handler ): ResponseInterface
    {
        setcookie( 'DT_HOME', $this->get_bypass_value(), time() + 600, '/' );

        return $handler->handle( $request );
    }
}
