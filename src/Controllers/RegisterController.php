<?php

namespace DT\Home\Controllers;

use DT\Home\CodeZone\WPSupport\Router\ServerRequestFactory;
use DT\Home\GuzzleHttp\Psr7\ServerRequest as Request;
use DT\Home\Psr\Http\Message\ResponseInterface;
use function DT\Home\extract_request_input;
use function DT\Home\plugin_url;
use function DT\Home\redirect;
use function DT\Home\template;
use function DT\Home\route_url;

class RegisterController {

    /**
     * Show the register form.
     *
     * @param Request $request The HTTP request.
     *
     * @return ResponseInterface The rendered template.
     */
    public function show( Request $request ) {
        $params = $request->getQueryParams();
        $form_action = route_url( 'register' );
        $login_url   = route_url( 'login' );
        $error       = sanitize_text_field( $params['error'] ?? '' );
        $username    = sanitize_text_field( $params['username'] ?? '' );
        $email       = sanitize_email( $params['email'] ?? '' );
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

    /**
     * Process the registration form.
     *
     * @param Request $request The HTTP request.
     *
     * @return ResponseInterface The response.
     */
	public function process( Request $request ) {

		$input = extract_request_input( $request );

		$username         = sanitize_text_field( $input['username'] ?? '' );
		$email            = sanitize_email( $input['email'] ?? '' );
		$password         = $input['password'] ?? '';
		$confirm_password = $input['confirm_password'] ?? '';

		if ( ! $username || ! $password || ! $email ) {
			return $this->show(
				ServerRequestFactory::with_query_params([
					'error'    => 'Please fill out all fields.',
					'username' => $username,
					'email'    => $email,
				])
			);
		}

		if ( $confirm_password !== $password ) {
			return $this->show(
				ServerRequestFactory::with_query_params([
					'error'    => 'Passwords do not match',
					'username' => $username,
					'email'    => $email,
				])
			);
		}

		$user = wp_create_user( $username, $password, $email );

		if ( is_wp_error( $user ) ) {
			$error = $user->get_error_message();

			return $this->show(ServerRequestFactory::with_query_params([
				'error'    => $error,
				'username' => $username,
				'email'    => $email,
			]));
		}

		$user_obj = get_user_by( 'id', $user );
		wp_set_current_user( $user );
		wp_set_auth_cookie( $user_obj->ID );

		if ( ! $user ) {
			return $this->show(ServerRequestFactory::with_query_params([
				'error'    => __( 'An unexpected error has occurred.', 'dt_home' ),
				'username' => $username,
				'email'    => $email,
			]));
		}

		return redirect( route_url() );
	}
}
