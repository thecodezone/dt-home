<?php

namespace DT\Launcher\Controllers;

use DT\Launcher\Illuminate\Http\Request;
use DT\Launcher\Illuminate\Http\Response;
use function DT\Launcher\template;
use function DT\Launcher\view;
use DT\Launcher\Illuminate\Http\RedirectResponse;

class UserController {

	/**
	 * You can also return a string or array from a controller method,
	 * it will be automatically added to the response object.
	 *
	 * @param Request $request The request object.
	 * @param Response $response The response object.
	 */
	public function current( Request $request, Response $response ) {

		return template( 'user', [
			'user' => wp_get_current_user()
		] );
	}

	/**
	 * Fetches and displays the details of a user.
	 *
	 * @param Request $request The request object.
	 * @param Response $response The response object.
	 * @param int $id Mapped from the ID route parameter.
	 */
	public function show( Request $request, Response $response, $id ) {
		$user = get_user_by( 'id', $id );

		return template( 'user', [
			'user' => $user
		] );
	}

	public function login( Request $request, Response $response){

			return view( 'auth/login' );
	}

}
