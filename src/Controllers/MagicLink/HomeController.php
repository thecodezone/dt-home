<?php

namespace DT\Home\Controllers\MagicLink;

use DT\Home\CodeZone\WPSupport\Router\ResponseFactory;
use DT\Home\Psr\Http\Message\ResponseInterface;
use DT\Home\Psr\Http\Message\ServerRequestInterface as Request;
use DT\Home\Services\Apps;
use function DT\Home\container;
use function DT\Home\magic_url;
use function DT\Home\redirect;
use function DT\Home\response;
use function DT\Home\route_url;
use function DT\Home\template;

/**
 *
 */
class HomeController
{
    /**
     * Shows the index page with user data and magic link.
     *
     * @param Request $request The request object.
     * @param mixed $params The parameters containing the key.
     *
     * @return ResponseInterface The response containing the rendered template.
     */
    public function show( Request $request, $params )
    {
        $key = $params['key'];
        $apps = container()->get( Apps::class );
        $user = get_current_user_id();
        $apps_array = $apps->for_user( $user );
        $data = json_encode( $apps_array );
        $hidden_data = json_encode( $apps_array );
        $app_url = magic_url( '', $key );
        $magic_link = $app_url . '/share';

        return template('index', compact(
            'user',
            'data',
            'app_url',
            'magic_link',
            'hidden_data'
        ));
    }

    /**
     * This method is responsible for updating the "is_hidden" status of an app.
     *
     * @param Request $request The request object.
     *
     * @return ResponseInterface The response containing the rendered template.
     */
    public function update_hide_app( Request $request )
    {
        $apps = container()->get( Apps::class );
        $data = $request->getParsedBody();

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
    public function update_unhide_app( Request $request )
    {
        $apps = container()->get( Apps::class );
        $data = $request->getParsedBody();

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
    public function update_app_order( Request $request )
    {
        $data = $request->getParsedBody();

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
