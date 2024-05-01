<?php

namespace DT\Home\Controllers;

use DT\Home\Illuminate\Http\Request;
use DT\Home\Illuminate\Http\Response;
use function DT\Home\plugin_url;
use function DT\Home\redirect;
use function DT\Home\template;
use function DT\Home\route_url;

class RegisterController {

	/**
	 * Process the register form
	 */
	public function process( Request $request, Response $response ) {

		$username         = $request->input( 'username' ?? '' );
		$email            = $request->input( 'email' ?? '' );
		$password         = $request->input( 'password' ?? '' );
		$confirm_password = $request->input( 'confirm_password' ?? '' );

		if ( ! $username || ! $password || ! $email ) {
			return $this->register( [
				'error'    => 'Please fill out all fields.',
				'username' => $username,
				'email'    => $email,
				'password' => $password
			] );
		}

		if ( $confirm_password !== $password ) {
			return $this->register( [
				'error'    => 'Passwords do not match',
				'username' => $username,
				'email'    => $email,
				'password' => $password
			] );
		}

		$user = wp_create_user( $username, $password, $email );

		if ( is_wp_error( $user ) ) {
			$error = $user->get_error_message();

			return $this->register( [ 'error' => $error ] );
		}

		$user_obj = get_user_by( 'id', $user );
		wp_set_current_user( $user );
		wp_set_auth_cookie( $user_obj->ID );


		if ( ! $user ) {
			return $this->register( [ 'error' => esc_html_e( 'An unexpected error has occurred.', 'dt_home' ) ] );
		}

		return redirect( '/home' );
	}

	/**
	 * Show the register template
	 */
	public function register( $params = [] ) {
		$form_action = route_url( 'register' );
		$login_url   = route_url( 'login' );
		$error       = $params['error'] ?? '';
		$username    = $params['username'] ?? '';
		$email       = $params['email'] ?? '';
		$password    = $params['password'] ?? '';
		$logo_path   = plugin_url( 'resources/img/logo-color.png' );

		return template( 'auth/register', [

			'form_action' => $form_action,
			'login_url'   => $login_url,
			'username'    => $username,
			'email'       => $email,
			'password'    => $password,
			'logo_path'   => $logo_path,
			'error'       => $error
		] );
	}
}
