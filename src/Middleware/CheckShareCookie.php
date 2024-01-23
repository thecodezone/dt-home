<?php

namespace DT\Launcher\Middleware;

use DT\Launcher\CodeZone\Router\Middleware\Middleware;
use DT\Launcher\Illuminate\Http\RedirectResponse;
use DT\Launcher\Illuminate\Http\Request;
use DT\Launcher\Symfony\Component\HttpFoundation\Response;

class CheckShareCookie implements Middleware {
	public function handle( Request $request, Response $response, callable $next ) {

        $cookie = $request->cookies->get('dt_launcher_share');

        if($cookie){
            $this->add_session_leader('dt_launcher_share');
        }

		return $next( $request, $response );
	}

    public function add_session_leader() {
        if ( ! isset( $_COOKIE['dt_launcher_share'] ) ) {
            return;
        }
        $leader_id = sanitize_text_field( wp_unslash( (string) $_COOKIE['dt_launcher_share'] ) ) ?? null;
        if ( ! $leader_id ) {
            return;
        }

        $contact        = Disciple_Tools_Users::get_contact_for_user( get_current_user_id() );
        $contact_record = DT_Posts::get_post( 'contacts', $contact, true, false );
        $leader         = DT_Posts::get_post( 'contacts', $leader_id, true, false );

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

            DT_Posts::update_post( 'contacts', $contact, $fields, true, false );
        }

        if ( isset( $_COOKIE['dt_launcher_share'] ) ) {
            unset( $_COOKIE['dt_launcher_share'] );
            setcookie( 'dt_launcher_share', '', time() - 3600, '/' );
        }
    }
}
