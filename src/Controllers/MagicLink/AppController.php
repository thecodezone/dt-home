<?php

namespace DT\Home\Controllers\MagicLink;

use DT\Home\GuzzleHttp\Psr7\ServerRequest as Request;
use DT\Home\Psr\Http\Message\ResponseInterface;
use DT\Home\Services\Apps;
use DT\Home\Sources\UserApps;
use function DT\Home\container;
use function DT\Home\extract_request_input;
use function DT\Home\get_plugin_option;
use function DT\Home\magic_url;
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

    private $apps;

    private $user_apps;

    public function __construct( Apps $apps, UserApps $user_apps )
    {
        $this->apps = $apps;
        $this->user_apps = $user_apps;
    }

    /**
     * Display the app launcher
     *
     * @param Request $request The request object.
     * @param array $params The parameters.
     */
    public function index( Request $request, $params )
    {

        $key         = $params['key'];
        $user        = get_current_user_id();
        $apps_array  = $this->apps->for( $user );
        $data        = json_encode( $apps_array );
        $hidden_data = $data;
        $app_url     = magic_url( '', $key );
        $magic_link  = $app_url . '/share';
        $reset_apps  = get_plugin_option( 'reset_apps' );

        return template(
            'index',
            compact(
                'user',
                'data',
                'app_url',
                'magic_link',
                'hidden_data',
                'reset_apps'
            )
        );
    }

    /**
     * Displays the app based on the provided slug.
     *
     * @param Request $request The request object.
     * @param array   $params
     *
     * @return ResponseInterface The response object.
     */
    public function show( Request $request, $params )
    {
        // Fetch the app
        $slug = $params['slug'];
        $apps = container()->get( Apps::class );
        $user_id = get_current_user_id();
        $app  = $apps->find_for( $slug, $user_id );

        if ( ! $app ) {
            return response( __( 'Not Found', 'dt-home' ), 404 );
        }

        // Check if there is a custom action to render the app
        $action = has_action( 'dt_home_render' );
        if ( $action ) {
            add_action(
                namespace_string( 'filter_asset_queue' ),
                function ( $queue ) use ( $app ) {
                    // Don't filter assets
                }
            );
            do_action( 'dt_home_app_render', $app );
        }

        // Check if the app has a custom template
        $html = apply_filters( 'dt_home_app_template', '', $app );

        if ( $html ) {
            if ( $html instanceof ResponseInterface ) {
                return $html;
            }

            return response( $html );
        }

        // Check to see if the app has an iframe URL
        $url = apply_filters( 'dt_home_webview_url', ( $app['url'] ?? '' ), $app );
        if ( ! $url ) {
            // No URL found 404
            return response( __( 'Not Found', 'dt-home' ), 404 );
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
        $params = extract_request_input( $request );
        $result = $this->user_apps->hide( $params['slug'] );

        if ( ! $result['is_hidden'] ) {
            return response( [ 'message' => 'Failed to update visibility.' ], 500 );
        }

        return response( [ 'message' => 'App visibility updated' ] );
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
        $params = extract_request_input( $request );
        $result = $this->user_apps->unhide( $params['slug'] );

        if ( $result['is_hidden'] ) {
            return response( [ 'message' => 'Failed to update visibility.' ], 500 );
        }

        return response( [ 'message' => 'App visibility updated' ] );
    }


    /**
     * Updates the app order based on the provided request data.
     *
     * @param Request $request The request object containing the app order data.
     *
     * @return ResponseInterface
     */
    public function reorder( Request $request )
    {
        $data = extract_request_input( $request );

        // Iterate through each app in the data
        foreach ( $data as $key => $app ) {
            // Update the 'sort' field for each app based on its position in the array
            $data[$key]['sort'] = ( $key + 1 );
        }

        // Save the updated app order back to the database or storage
        update_user_option( get_current_user_id(), 'dt_home_apps', $data );

        return response( [ 'message' => 'App order updated' ] );
    }


    /**
     * Resets the user's apps by clearing the 'dt_home_apps' option
     *
     * @param Request $request The request object.
     *
     * @return ResponseInterface The response containing a success message.
     */
    public function reset_apps( Request $request )
    {
        $apps       = container()->get( Apps::class );
        $admin_apps = $apps->all();

        update_user_option( get_current_user_id(), 'dt_home_apps', $admin_apps );

        return response( [ 'message' => 'App order updated' ] );
    }
}
