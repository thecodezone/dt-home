<?php

namespace DT\Home\Controllers\MagicLink;

use DT\Home\GuzzleHttp\Psr7\ServerRequest as Request;
use DT\Home\Psr\Http\Message\ResponseInterface;
use DT\Home\Services\Apps;
use function DT\Home\container;
use function DT\Home\extract_request_input;
use function DT\Home\namespace_string;
use function DT\Home\response;
use function DT\Home\template;

/**
 * Class AppController
 *
 * Controls the display of application details.
 */
class AppController
{
    /**
     * Displays the app based on the provided slug.
     *
     * @param Request $request The request object.
     * @param array $params
     * @return ResponseInterface The response object.
     */
    public function show( Request $request, $params )
    {
        //Fetch the app
        $slug = $params['slug'];
        $apps = container()->get( Apps::class );
        $app = $apps->find( $slug );

        if ( !$app ) {
            return response( __( 'Not Found', 'dt_home' ), 404 );
        }

        //Check if there is a custom action to render the app
        $action = has_action( 'dt_home_render' );
        if ( $action ) {
            add_action(namespace_string( 'filter_asset_queue' ), function ( $queue ) use ( $app ) {
                //Don't filter assets
            });
            do_action( 'dt_home_app_render', $app );
        }

        //Check if the app has a custom template
        $html = apply_filters( 'dt_home_app_template', "", $app );

        if ( $html ) {
            if ( $html instanceof ResponseInterface ) {
                return $html;
            }
            return response( $html );
        }

        //Check to see if the app has an iframe URL
        $url = apply_filters( 'dt_home_webview_url', $app['url'] ?? '', $app );

        if ( !$url ) {
            //No URL found 404
            return response( __( 'Not Found', 'dt_home' ), 404 );
        }

        return template( 'web-view', compact( 'app', 'url' ) );
    }

    /**
     * This method is responsible for updating the "is_hidden" status of an app.
     *
     * @param Request $request The request object.
     *
     * @return ResponseInterface The response containing the rendered template.
     */
    public function hide( Request $request )
    {
        $apps = container()->get( Apps::class );
        $data = extract_request_input( $request );

        $apps_array = $apps->for_user( get_current_user_id() );

        // Find the app with the specified slug and update its 'is_hidden' status
        foreach ( $apps_array as $key => $app ) {
            if ( isset( $app['slug'] ) && $app['slug'] == $data['slug'] ) {
                $apps_array[$key]['is_hidden'] = 1; // Set 'is_hidden' to 1 (hide)
                break; // Exit the loop once the app is found and updated
            }
        }

        // Separate hidden and visible apps
        $hidden_apps = [];
        $visible_apps = [];

        foreach ( $apps_array as $app ) {
            if ( $app['is_hidden'] == 1 ) {
                $hidden_apps[] = $app;
            } else {
                $visible_apps[] = $app;
            }
        }

        // Sort visible apps by the 'sort' field
        usort($visible_apps, function ( $a, $b ) {
            return $a['sort'] <=> $b['sort'];
        });

        // Reset sort values for visible apps
        foreach ( $visible_apps as $index => $app ) {
            $visible_apps[$index]['sort'] = $index + 1;
        }

        // Add hidden apps back to the end
        foreach ( $hidden_apps as $hidden_app ) {
            $hidden_app['sort'] = count( $visible_apps ) + 1;
            $visible_apps[] = $hidden_app;
        }

        // Save the updated array back to the option
        update_user_option( get_current_user_id(), 'dt_home_apps', $visible_apps );

        return response( [ 'message' => 'App visibility and order updated' ] );
    }


    /**
     * This method is responsible for updating the "is_hidden" status of an app.
     *
     * @param Request $request The request object.
     *
     * @return ResponseInterface
     */
    public function unhide( Request $request )
    {
        $apps = container()->get( Apps::class );
        $data = extract_request_input( $request );

        $apps_array = $apps->for_user( get_current_user_id() );

        // Find the app with the specified ID and update its 'is_hidden' status
        foreach ( $apps_array as $key => $app ) {
            if ( isset( $app['slug'] ) && $app['slug'] == $data['slug'] ) {
                $apps_array[$key]['is_hidden'] = 0; // Set 'is_hidden' to 1 (hide)
                break; // Exit the loop once the app is found and updated
            }
        }

        // Separate hidden and visible apps
        $hidden_apps = [];
        $visible_apps = [];

        foreach ( $apps_array as $app ) {
            if ( $app['is_hidden'] == 1 ) {
                $hidden_apps[] = $app;
            } else {
                $visible_apps[] = $app;
            }
        }

        // Sort visible apps by the 'sort' field
        usort($visible_apps, function ( $a, $b ) {
            return $a['sort'] <=> $b['sort'];
        });

        // Reset sort values for visible apps
        foreach ( $visible_apps as $index => $app ) {
            $visible_apps[$index]['sort'] = $index + 1;
        }

        // Add hidden apps back to the end
        foreach ( $hidden_apps as $hidden_app ) {
            $hidden_app['sort'] = count( $visible_apps ) + 1;
            $visible_apps[] = $hidden_app;
        }

        // Save the updated array back to the option
        update_user_option( get_current_user_id(), 'dt_home_apps', $visible_apps );

        return response( [ 'message' => 'App visibility updated' ] );
    }

    /**
     * Updates the app order based on the provided request data.
     *
     * @param Request $request The request object containing the app order data.
     *
     * @return ResponseInterface
     *
     */
    public function reorder( Request $request )
    {
        $data = extract_request_input( $request );

        // Iterate through each app in the data
        foreach ( $data as $key => $app ) {
            // Update the 'sort' field for each app based on its position in the array
            $data[$key]['sort'] = $key + 1;
        }

        // Save the updated app order back to the database or storage
        update_user_option( get_current_user_id(), 'dt_home_apps', $data );

        return response( [ 'message' => 'App order updated' ] );
    }
}