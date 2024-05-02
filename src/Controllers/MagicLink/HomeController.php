<?php

namespace DT\Home\Controllers\MagicLink;

use DT\Home\Illuminate\Http\Request;
use DT\Home\Illuminate\Http\Response;
use DT\Home\Services\Apps;
use function DT\Home\magic_url;
use function DT\Home\template;

/**
 *
 */
class HomeController
{
	/**
	 * This method is responsible for rendering the "show" page.
	 *
	 * @param Request $request The request object.
	 * @param Response $response The response object.
	 * @param Apps $apps The instance of the Apps class.
	 * @param string $key The key parameter.
	 *
	 * @return \Symfony\Component\HttpFoundation\Response The rendered template.
	 */
	public function show( Request $request, Response $response, Apps $apps, $key )
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

	/**
	 * This method is responsible for retrieving and returning user data.
	 *
	 * @param Request $request The request object.
	 * @param Response $response The response object.
	 * @param string $key The key parameter.
	 *
	 * @return \Symfony\Component\HttpFoundation\Response The response with the user data.
	 */
	public function data( Request $request, Response $response, $key )
    {
        $user = wp_get_current_user();
        $data = [
            'user_login' => $user->user_login,
        ];
        $response->setContent( $data );

        return $response;
    }

	/**
	 * This method is responsible for updating the "is_hidden" status of an app.
	 *
	 * @param Request $request The request object.
	 * @param Response $response The response object.
	 * @param Apps $apps The instance of the Apps class.
	 * @param string $key The key parameter.
	 *
	 * @return \Symfony\Component\HttpFoundation\Response The updated response.
	 */
	public function update_hide_app( Request $request, Response $response, Apps $apps, $key )
    {
        $data = $request->json()->all();

        $apps_array = $apps->for_user( get_current_user_id() );

        // Find the app with the specified ID and update its 'is_hidden' status
        foreach ( $apps_array as $key => $app ) {
            if ( isset( $app['slug'] ) && $app['slug'] == $data['slug'] ) {
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


	/**
	 * This method is responsible for updating the 'is_hidden' status of an app.
	 *
	 * @param Request $request The request object.
	 * @param Response $response The response object.
	 * @param Apps $apps The instance of the Apps class.
	 * @param string $key The key parameter.
	 *
	 * @return \Symfony\Component\HttpFoundation\Response The updated response.
	 */
	public function update_unhide_app( Request $request, Response $response, Apps $apps, $key )
    {
        $data = $request->json()->all();

        $apps_array = $apps->for_user( get_current_user_id() );

        // Find the app with the specified ID and update its 'is_hidden' status
        foreach ( $apps_array as $key => $app ) {
            if ( isset( $app['slug'] ) && $app['slug'] == $data['slug'] ) {
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

	/**
	 * Updates the app order based on the provided request data.
	 *
	 * @param Request $request The request object containing the app order data.
	 * @param Response $response The response object to be returned.
	 * @param mixed $key The key parameter that is not used in the code.
	 *
	 * @return Response The updated response object.
	 */
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

	/**
	 * Opens the desired app based on the provided slug.
	 *
	 * @param Apps $apps The Apps object containing all the available apps.
	 * @param string $slug The slug of the desired app.
	 *
	 * @return Response The template response object.
	 */
	public function open_app( Apps $apps, $slug )
    {
        $apps_array = $apps->all();

        $desired_app = null;

        foreach ( $apps_array as $app ) {
            if ( ( is_array( $app ) && $app['slug'] == $slug ) || ( is_object( $app ) && $app->slug == $slug ) ) {
                $desired_app = $app;
                break;
            }
        }

        return template( 'web-view', compact( 'desired_app' ) );
    }
}
