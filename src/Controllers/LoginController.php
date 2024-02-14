<?php

namespace DT\Home\Controllers;

use DT\Home\Illuminate\Http\Request;
use DT\Home\Illuminate\Http\Response;
use function DT\Home\redirect;
use function DT\Home\template;

class LoginController {

	/**
	 * Process the login form
	 */
	public function login_process( Request $request, Response $response ) {
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
	 * Show the login template
	 */
	public function login( $params = [] ) {
		$register_url = '/home/register';
		$form_action  = '/home/login';
		$username     = $params['username'] ?? '';
		$password     = $params['password'] ?? '';
		$error        = $params['error'] ?? '';
		$logo_path    = get_site_url() . '/wp-content/plugins/dt-home/resources/img/logo-color.png';
		$reset_url    = wp_lostpassword_url( $this->get_link_url() );

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

	public function get_link_url() {
		return get_site_url( null, 'home' );
	}

	public function logout( $params = [] ) {
		wp_logout();

		return redirect( '/home/login' );
		exit;
	}
}
