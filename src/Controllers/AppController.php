<?php

namespace DT\Home\Controllers;

use DT\Home\Illuminate\Http\Request;
use DT\Home\Illuminate\Http\Response;
use DT\Home\Services\Apps;
use function DT\Home\collect;
use function DT\Home\namespace_string;
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
     * @param Response $response The response object.
     * @param Apps $apps The apps service.
     * @param mixed $key The key parameter.
     * @param string $slug The slug parameter.
     * @return Response The response object.
     */
    public function show( Request $request, Response $response, Apps $apps, $key, $slug )
    {
        //Fetch the app
        $app = collect( $apps->all() )->where( 'slug', $slug )->first();

        if ( !$app ) {
            return $response->setStatusCode( 404 )->setContent( 'Not Found' );
        }

        //Check if there is a custom action to render the app
        $action = has_action( 'dt_home_app_render' );
        if ( $action ) {
			add_action(namespace_string( 'filter_asset_queue' ), function ( $queue ) use ( $app ) {
				//Don't filter assets
			});
            do_action( 'dt_home_app_render', $app );
            exit;
        }

        //Check if the app has a custom template
        $html = apply_filters( 'dt_home_app_template', "", $app );

        if ( $html ) {
            return $response->setContent( $html );
        }

        //Check to see if the app has an iframe URL
        $url = apply_filters( 'dt_home_webview_url', $app['url'] ?? '', $app );

        if ( !$url ) {
            //No URL found 404
            return $response->setStatusCode( 404 )->setContent( 'Not Found' );
        }

        return $response->setContent(
            template( 'web-view', compact( 'app', 'url' ) )
        );
    }
}
