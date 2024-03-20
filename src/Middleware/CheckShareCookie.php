<?php

namespace DT\Home\Middleware;

use DT\Home\CodeZone\Router\Middleware\Middleware;
use DT\Home\Illuminate\Http\RedirectResponse;
use DT\Home\Illuminate\Http\Request;
use DT\Home\Symfony\Component\HttpFoundation\Response;

/**
 * Class CheckShareCookie
 *
 * This class implements the Middleware interface and is responsible for checking
 * the value of the "dt_home_share" cookie and perform the necessary actions.
 */
class CheckShareCookie implements Middleware {
	/**
	 * Handle the incoming request.
	 *
	 * @param Request $request The incoming request
	 * @param Response $response The response object
	 * @param callable $next The next handler in the middleware stack
	 *
	 * @return mixed The result of the next handler
	 */
	public function handle( Request $request, Response $response, callable $next ) {

		if ( ! is_user_logged_in() ) {
			return $next( $request, $response );
		}

		$leader_id = $request->cookies->get( 'dt_home_share' );

		if ( $leader_id ) {
			try {
				$this->add_leader( $leader_id );
			} catch ( \Exception $e ) {
				$this->remove_cookie();
			}
		}

		return $next( $request, $response );
	}

	/**
	 * Add a leader to a contact's coached_by field and update assigned_to field.
	 *
	 * @param int $leader_id The ID of the leader to be added.
	 *
	 * @return void
	 */
	public function add_leader( $leader_id ) {
		if ( ! $leader_id ) {
			return;
		}

		$contact = \Disciple_Tools_Users::get_contact_for_user( get_current_user_id() );

		if ( $leader_id == $contact ) {
			$this->remove_cookie();

			return;
		}

		$contact_record = \DT_Posts::get_post( 'contacts', $contact, true, false );
		$leader         = \DT_Posts::get_post( 'contacts', $leader_id, true, false );

		if ( ! $contact_record || ! $leader ) {
			$this->remove_cookie();
		}

		if ( ! count( $contact_record['coached_by'] ) ) {
			$fields = [
				"coached_by"  => [
					"values"       => [
						[ "value" => (string) $leader_id ],
					],
					"force_values" => false
				],
				'assigned_to' => (string) $leader['corresponds_to_user']
			];


			\DT_Posts::update_post( 'contacts', $contact, $fields, true, false );
		}

		$this->remove_cookie();
	}

	/**
	 * Removes the 'dt_home_share' cookie if it exists.
	 *
	 * @return void
	 */
	public function remove_cookie() {
		if ( isset( $_COOKIE['dt_home_share'] ) ) {
			unset( $_COOKIE['dt_home_share'] );
			setcookie( 'dt_home_share', '', time() - 3600, '/' );
		};
	}
}
