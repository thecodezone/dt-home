<?php

namespace DT\Launcher\Middleware;

use DT\Launcher\CodeZone\Router\Middleware\Middleware;
use DT\Launcher\Illuminate\Http\RedirectResponse;
use DT\Launcher\Illuminate\Http\Request;
use DT\Launcher\Symfony\Component\HttpFoundation\Response;

class LoggedIn implements Middleware {
	public function handle( Request $request, Response $response, $next ) {

		$require_login = get_option( 'dt_launcher_require_login' );

		if ( ! is_user_logged_in() && $require_login === 1 ) {
			$response = new RedirectResponse( "/launcher/login", 302 );
		}

		return $next( $request, $response );
	}
}
