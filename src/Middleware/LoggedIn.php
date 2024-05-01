<?php

namespace DT\Home\Middleware;

use DT\Home\CodeZone\Router\Middleware\Middleware;
use DT\Home\Illuminate\Http\RedirectResponse;
use DT\Home\Illuminate\Http\Request;
use DT\Home\Symfony\Component\HttpFoundation\Response;
use function DT\Home\route_url;

class LoggedIn implements Middleware {
	public function handle( Request $request, Response $response, $next ) {

		$require_login = get_option( 'dt_home_require_login' );

		if ( ! is_user_logged_in() && $require_login == 1 ) {
			$response = new RedirectResponse( route_url( "login" ), 302 );
		}

		return $next( $request, $response );
	}
}
