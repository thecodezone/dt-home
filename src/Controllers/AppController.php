<?php

namespace DT\Home\Controllers;

use DT\Home\Illuminate\Http\Request;
use DT\Home\Illuminate\Http\Response;
use function DT\Home\template;
use function DT\Home\collect;

/**
 * Class AppController
 *
 * Controls the display of application details.
 */
class AppController {
	/**
	 * Displays the application in an iframe webview
	 *
	 * This method retrieves the application with the provided ID from the "dt_home_apps" option and displays its details.
	 * If the application is not found, a 404 error response is returned.
	 *
	 * @param Request $request The request object.
	 * @param Response $response The response object.
	 * @param int $id The ID of the application to display.
	 *
	 * @return Response The response object containing the rendered application details.
	 */
	public function show( Request $request, Response $response, $id ) {
		//Fetch the app
		$app = collect( get_option( 'dt_home_apps', [] ) )->where( 'id', $id )->first();
		if ( ! $app ) {
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
		if ( ! $url ) {
			//No URL found 404
			return $response->setStatusCode( 404 )->setContent( 'Not Found' );
		}

		return $response->setContent(
			template( 'web-view', compact( 'app', 'url' ) )
		);
	}
}
