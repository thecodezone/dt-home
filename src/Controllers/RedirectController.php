<?php

namespace DT\Launcher\Controllers;

use DT\Launcher\Illuminate\Http\Request;
use DT\Launcher\Illuminate\Http\Response;
use function DT\Launcher\magic_url;
use function DT\Launcher\redirect;

class RedirectController {

	/**
	 * Redirects to the URL for the launcher app. This uses the
	 * auth middleware, so the user will be redirected to
	 * the login page if they are not logged in.
	 *
	 * @param Request $request The HTTP request object.
	 * @param Response $response The HTTP response object.
	 */
	public function show( Request $request, Response $response ) {
		return redirect( magic_url() );
	}
}
