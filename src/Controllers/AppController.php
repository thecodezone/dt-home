<?php

namespace DT\Home\Controllers;

use DT\Home\Illuminate\Http\Request;
use DT\Home\Illuminate\Http\Response;
use DT\Home\Services\Apps;
use function DT\Home\collect;
use function DT\Home\template;

/**
 * Class AppController
 *
 * Controls the display of application details.
 */
class AppController
{
	/**
	 * This method is responsible for rendering an app.
	 *
	 * @param Response $response The HTTP response object.
	 * @param Apps $apps The instance of the Apps class.
	 * @param string $slug The slug of the app.
	 *
	 * @return Response The HTTP response object.
	 */
    public function show( Response $response, Apps $apps, $slug )
    {
        //Fetch the app
        $app = $apps->get_by_slug( $slug );

        if ( !$app ) {
            return $response->setStatusCode( 404 )->setContent( 'Not Found' );
        }

        //Check if there is a custom action to render the app
        $action = has_action( 'dt_home_app_render' );

        if ( $action ) {
            do_action( 'dt_home_app_render', $app );
            exit;
        }

        //Check if the app has a custom template
        $html = apply_filters( 'dt_home_app_render', "", $app );

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
