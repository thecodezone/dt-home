<?php

namespace DT\Launcher\Middleware;

use DT\Launcher\CodeZone\Router\Middleware\Middleware;
use DT\Launcher\Illuminate\Http\RedirectResponse;
use DT\Launcher\Illuminate\Http\Request;
use DT\Launcher\Symfony\Component\HttpFoundation\Response;

class LoggedIn implements Middleware {
	public function handle( Request $request, Response $response, $next ) {
		if ( ! is_user_logged_in() ) {
			$response = new RedirectResponse( wp_login_url( $request->getUri() ), 302 );
		}

		return $next( $request, $response );
	}
}