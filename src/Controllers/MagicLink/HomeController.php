<?php

namespace DT\Home\Controllers\MagicLink;

use DT\Home\Illuminate\Http\Request;
use DT\Home\Illuminate\Http\Response;
use DT\Home\Services\Apps;
use function DT\Home\magic_url;
use function DT\Home\template;

class HomeController
{
    public function show( Apps $apps, $key )
    {
        $user = get_current_user_id();
        $subpage_url = magic_url( 'subpage', $key );

        $apps_array = $apps->for_user( $user );

        $data = json_encode( $apps_array );
        $hidden_data = json_encode( $apps_array );

        $app_url = magic_url( '', $key );
        $magic_link = $app_url . '/share';

        return template('index', compact(
            'user',
            'subpage_url',
            'data',
            'app_url',
            'magic_link',
            'hidden_data'
        ));
    }

    public function show_hidden_apps( Request $request, Response $response, $key )
    {
        $user = wp_get_current_user();
        $subpage_url = magic_url( 'subpage', $key );
        $magic_link = magic_url();

        $apps_array = get_user_option( 'dt_home_apps', [] );

        // Fallback to default option if user option is not set
        if ( !$apps_array ) {
            $apps_array = get_option( 'dt_home_apps', [] );
        }
        $data = json_encode( $apps_array );

        $app_url = magic_url( '', $key );

        return template('hidden-apps', compact(
            'user',
            'subpage_url',
            'data',
            'app_url',
            'magic_link'
        ));
    }

    public function data( Request $request, Response $response, $key )
    {
        $user = wp_get_current_user();
        $data = [
            'user_login' => $user->user_login,
        ];
        $response->setContent( $data );

        return $response;
    }

    public function update_hide_app( Request $request, Response $response, $key )
    {
        $data = $request->json()->all();

        // Assuming $data contains 'id' and 'is_hidden'
        $app_id = $data['id'];

        $apps_array = get_user_option( 'dt_home_apps', get_current_user_id() );

        // Fallback to default option if user option is not set
        if ( $apps_array === false ) {
            $apps_array = get_option( 'dt_home_apps' );
        }

        // Find the app with the specified ID and update its 'is_hidden' status
        foreach ( $apps_array as $key => $app ) {
            if ( isset( $app['id'] ) && $app['id'] == $app_id ) {
                $apps_array[$key]['is_hidden'] = 1; // Set 'is_hidden' to 1 (hide)
                break; // Exit the loop once the app is found and updated
            }
        }
        // Save the updated array back to the option

        update_user_option( get_current_user_id(), 'dt_home_apps', $apps_array );

        $response_data = [ 'message' => 'App visibility updated' ];

        $response->setContent( json_encode( $response_data ) );

        return $response;
    }


    public function update_unhide_app( Request $request, Response $response, $key )
    {
        $data = $request->json()->all();

        // Assuming $data contains 'id' and 'is_hidden'
        $app_id = $data['id'];

        $apps_array = get_user_option( 'dt_home_apps', get_current_user_id() );

        // Fallback to default option if user option is not set
        if ( $apps_array === false ) {
            $apps_array = get_option( 'dt_home_apps' );
        }

        // Find the app with the specified ID and update its 'is_hidden' status
        foreach ( $apps_array as $key => $app ) {
            if ( isset( $app['id'] ) && $app['id'] == $app_id ) {
                $apps_array[$key]['is_hidden'] = 0; // Set 'is_hidden' to 1 (hide)
                break; // Exit the loop once the app is found and updated
            }
        }
        // Save the updated array back to the option
        update_user_option( get_current_user_id(), 'dt_home_apps', $apps_array );

        $response_data = [ 'message' => 'App visibility updated' ];

        $response->setContent( json_encode( $response_data ) );

        return $response;
    }

    public function update_app_order( Request $request, Response $response, $key ): Response
    {
        $data = $request->json()->all();
        // Iterate through each app in the data
        foreach ( $data as $key => $app ) {
            // Update the 'sort' field for each app based on its position in the array
            $data[$key]['sort'] = $key + 1;
        }
        // Save the updated app order back to the database or storage
        update_user_option( get_current_user_id(), 'dt_home_apps', $data );

        $response_data = [ 'message' => 'App order updated' ];

        $response->headers->set( 'Content-Type', 'application/json' );

        $response->setContent( json_encode( $response_data ) );

        return $response;
    }

    public function open_app( $slug )
    {
        $apps_array = get_option( 'dt_home_apps', [] );

        $desired_app = null;

        foreach ( $apps_array as $app ) {
            if ( ( is_array( $app ) && $app['id'] == $slug ) || ( is_object( $app ) && $app->id == $slug ) ) {
                $desired_app = $app;
                break;
            }
        }

        return template( 'web-view', compact( 'desired_app' ) );
    }
}
