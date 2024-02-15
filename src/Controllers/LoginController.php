<?php

namespace DT\Home\Controllers;

use DT\Home\Illuminate\Http\Request;
use DT\Home\Illuminate\Http\Response;
use function DT\Home\redirect;
use function DT\Home\template;
use function DT\Home\plugin_url;

/**
 * Class LoginController
 *
 * This class handles user login and authentication.
 */
class LoginController {

	/**
	 * Processes the login request.
	 *
	 * @param Request $request The request object.
	 * @param Response $response The response object.
	 *
	 * @return Response The response object.
	 */
	public function process( Request $request, Response $response ) {
		global $errors;

		$username = $request->input( 'username' ?? '' );
		$password = $request->input( 'password' ?? '' );

		$user = wp_authenticate( $username, $password );

		if ( is_wp_error( $user ) ) {
			//phpcs:ignore
			$errors = $user;
			$error  = $errors->get_error_message();
			$error  = apply_filters( 'login_errors', $error );

			//If the error links to lost password, inject the 3/3rds redirect
			$error = str_replace( '?action=lostpassword', '?action=lostpassword?&redirect_to=/', $error );

			return $this->login( [ 'error' => $error, 'username' => $username, 'password' => $password ] );
		}

		wp_set_auth_cookie( $user->ID );

		if ( ! $user ) {
			return $this->login( [ 'error' => esc_html_e( 'An unexpected error has occurred.', 'dt_home' ) ] );
		}

		wp_set_current_user( $user->ID );

		return redirect( '/home' );
	}

	/**
	 * Renders the login template with the provided parameters.
	 *
	 * @param array $params {
	 *     An array of parameters.
	 *
	 * @type string $username The username input value. Default empty string.
	 * @type string $password The password input value. Default empty string.
	 * @type string $error The error message to display. Default empty string.
	 * }
	 *
	 * @return Response The response object.
	 */
	public function login( $params = [] ) {
		$register_url = '/home/register';
		$form_action  = '/home/login';
		$username     = $params['username'] ?? '';
		$password     = $params['password'] ?? '';
		$error        = $params['error'] ?? '';
		$logo_path    = plugin_url( 'resources/img/logo-color.png' );
		$reset_url    = wp_lostpassword_url( plugin_url() );

		return template( 'auth/login', [
			'register_url' => $register_url,
			'form_action'  => $form_action,
			'username'     => $username,
			'password'     => $password,
			'logo_path'    => $logo_path,
			'reset_url'    => $reset_url,
			'error'        => $error
		] );
	}

	/**
	 * Logs the user out and redirects them to the login page.
	 *
	 * @param array $params Additional parameters (optional).
	 *
	 * @return Response The response object.
	 */
	public function logout( $params = [] ) {
		wp_logout();

		return redirect( '/home/login' );
		exit;
	}
}
