<?php

namespace DT\Home\Middleware;

use DT\Home\CodeZone\Router\Middleware\Middleware;
use DT\Home\Illuminate\Http\RedirectResponse;
use DT\Home\Illuminate\Http\Request;
use DT\Home\Plugin;
use DT\Home\Symfony\Component\HttpFoundation\Response;

class LoggedOut implements Middleware {

	public function handle( Request $request, Response $response, $next ) {

		$require_login = get_option( 'dt_home_require_login' );

		if ( ! $require_login || is_user_logged_in() ) {
			$response = new RedirectResponse( "/" . Plugin::HOME_ROUTE, 302 );
		}

		return $next( $request, $response );
	}
}
