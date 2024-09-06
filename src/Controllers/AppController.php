<?php

namespace DT\Home\Controllers;

use DT\Home\GuzzleHttp\Psr7\ServerRequest as Request;
use DT\Home\Psr\Http\Message\ResponseInterface;
use DT\Home\Services\Apps;
use function DT\Home\container;
use function DT\Home\namespace_string;
use function DT\Home\template;
use function DT\Home\response;

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
}
