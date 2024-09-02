<?php

namespace DT\Home\Middleware;

use DT\Home\CodeZone\Router\Middleware\Middleware;
use DT\Home\Illuminate\Http\Request;
use DT\Home\Psr\Http\Message\ResponseInterface;
use DT\Home\Psr\Http\Message\ServerRequestInterface;
use DT\Home\Psr\Http\Server\MiddlewareInterface;
use DT\Home\Psr\Http\Server\RequestHandlerInterface;
use DT\Home\Symfony\Component\HttpFoundation\Response;

class UnCached implements MiddlewareInterface {
	public function process( ServerRequestInterface $request, RequestHandlerInterface $handler ): ResponseInterface
    {
	    header( 'Cache-Control: uncached' );

		return $handler->handle( $request );
    }
}
