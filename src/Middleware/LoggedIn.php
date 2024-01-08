<?php

namespace DT\Launcher\Middleware;

use DT\Launcher\CodeZone\Router\Middleware\Middleware;
use DT\Launcher\Illuminate\Http\RedirectResponse;
use DT\Launcher\Illuminate\Http\Request;
use DT\Launcher\Symfony\Component\HttpFoundation\Response;
use function DT\Launcher\view;

class LoggedIn implements Middleware {
	public function handle( Request $request, Response $response, $next ) {

		$require_login = get_option('require_login');

		if ($require_login === 1) {
			return view( 'auth/login' );
		}else{
			$response = new RedirectResponse( wp_login_url( $request->getUri() ), 302 );
		}

		return $next( $request, $response );
	}
}