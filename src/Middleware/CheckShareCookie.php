<?php

namespace DT\Home\Middleware;

use DT\Home\CodeZone\Router\Middleware\Middleware;
use DT\Home\Illuminate\Http\RedirectResponse;
use DT\Home\Illuminate\Http\Request;
use DT\Home\Symfony\Component\HttpFoundation\Response;

class CheckShareCookie implements Middleware {
	public function handle( Request $request, Response $response, callable $next ) {

        $cookie = $request->cookies->get('dt_home_share');

        if($cookie){
            $this->add_session_leader('dt_home_share');
        }

		return $next( $request, $response );
	}

    public function add_session_leader() {
        if ( ! isset( $_COOKIE['dt_home_share'] ) ) {
            return;
        }
        $leader_id = sanitize_text_field( wp_unslash( (string) $_COOKIE['dt_home_share'] ) ) ?? null;
        if ( ! $leader_id ) {
            return;
        }

        $contact        = \Disciple_Tools_Users::get_contact_for_user( get_current_user_id() );
        $contact_record = \DT_Posts::get_post( 'contacts', $contact, true, false );
        $leader         = \DT_Posts::get_post( 'contacts', $leader_id, true, false );

        if ( ! count( $contact_record['coached_by'] ) ) {
            $fields = [
                "coached_by"  => [
                    "values"       => [
                        [ "value" => $leader_id ],
                    ],
                    "force_values" => false
                ],
                'assigned_to' => (string) $leader['corresponds_to_user']
            ];

            //\DT_Posts::update_post( 'contacts', $contact, $fields, true, false );
        }

        if ( isset( $_COOKIE['dt_home_share'] ) ) {
            unset( $_COOKIE['dt_home_share'] );
            setcookie( 'dt_home_share', '', time() - 3600, '/' );
        }
    }
}
