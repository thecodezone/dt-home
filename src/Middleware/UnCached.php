<?php

namespace DT\Home\Middleware;

use DT\Home\Psr\Http\Message\ResponseInterface;
use DT\Home\Psr\Http\Message\ServerRequestInterface;
use DT\Home\Psr\Http\Server\MiddlewareInterface;
use DT\Home\Psr\Http\Server\RequestHandlerInterface;

class UnCached implements MiddlewareInterface {
	public function process( ServerRequestInterface $request, RequestHandlerInterface $handler ): ResponseInterface
    {
	    header( 'Cache-Control: uncached' );

		return $handler->handle( $request );
    }
}
