<?php

namespace DT\Home\Controllers;

use Disciple_Tools_Users;
use DT\Home\GuzzleHttp\Psr7\ServerRequest as Request;
use DT\Home\Psr\Http\Message\ResponseInterface;
use DT_Magic_URL;
use function DT\Home\magic_url;
use function DT\Home\redirect;
use function DT\Home\route_url;

class RedirectController
{

    /**
     * Redirects to the URL for the home app. This uses the
     * auth middleware, so the user will be redirected to
     * the login page if they are not logged in.
     *
     * @param Request $request The HTTP request object.
     * @return ResponseInterface $response
     */
    public function show( Request $request )
    {
        global $wpdb;

        if ( !is_user_logged_in() ) {
            return redirect( route_url( "login" ) );
        }

        $preference_key = 'dt-apps_launcher_magic_key';
        $meta_key = $wpdb->prefix . DT_Magic_URL::get_public_key_meta_key( 'apps', 'launcher' );


        if ( !$this->is_activated() ) {
            delete_user_meta( get_current_user_id(), $meta_key );
            delete_user_option( get_current_user_id(), $preference_key );
            add_user_meta( get_current_user_id(), $meta_key, DT_Magic_URL::create_unique_key() );

            Disciple_Tools_Users::app_switch( get_current_user_id(), $preference_key );
        }

        return redirect( magic_url() );
    }

    /**
     * Checks if the user is activated for the home launcher app.
     *
     * @return bool True if the user is activated, false otherwise.
     */
    public function is_activated(): bool
    {
        global $wpdb;
        $preference_key = 'dt-apps_launcher_magic_key';
        $meta_key = $wpdb->prefix . DT_Magic_URL::get_public_key_meta_key( 'apps', 'launcher' );

        $public = get_user_meta( get_current_user_id(), $meta_key, true );
        $secret = get_user_option( $preference_key );

        if ( $public === '' || $public === false || $public === '0' || $secret === '' || $secret === false || $secret === '0' ) {
            return false;
        }

        return true;
    }
}
