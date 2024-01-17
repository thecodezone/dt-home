<?php

namespace DT\Launcher\Controllers;

use DT\Launcher\Illuminate\Http\Request;
use DT\Launcher\Illuminate\Http\Response;
use function DT\Launcher\redirect;
use function DT\Launcher\template;

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
	 * Process the register form
	 */
	public function register_process( Request $request, Response $response ) {

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
			return $this->register( [ 'error' => esc_html_e( 'An unexpected error has occurred.', 'dt-launcher' ) ] );
		} else {

			return redirect( '/launcher' );
		}

	}

	/**
	 * Show the register template
	 */
	public function register( $params = [] ) {
		$form_action = '/launcher/register-process';
		$error       = $params['error'] ?? '';
		$username    = $params['username'] ?? '';
		$email       = $params['email'] ?? '';
		$password    = $params['password'] ?? '';

		return template( 'auth/register', [

			'form_action' => $form_action,
			'username'    => $username,
			'email'       => $email,
			'password'    => $password,
			'error'       => $error
		] );
	}

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
			return $this->login( [ 'error' => esc_html_e( 'An unexpected error has occurred.', 'dt-launcher' ) ] );
		}

		wp_set_current_user( $user->ID );

		return redirect( '/launcher' );
	}

	/**
	 * Show the login template
	 */
	public function login( $params = [] ) {
		$register_url = '/launcher/register';
		$form_action  = '/launcher/login-process';
		$username     = $params['username'] ?? '';
		$password     = $params['password'] ?? '';
		$error        = $params['error'] ?? '';

		return template( 'auth/login', [
			'register_url' => $register_url,
			'form_action'  => $form_action,
			'username'     => $username,
			'password'     => $password,
			'error'        => $error
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
}
