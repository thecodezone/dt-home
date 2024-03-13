<?php

namespace DT\Home\Controllers\Admin;

use DT\Home\Illuminate\Http\RedirectResponse;
use DT\Home\Illuminate\Http\Request;
use DT\Home\Illuminate\Http\Response;
use DT\Home\Services\SVGIconService;
use function DT\Home\view;

class AppSettingsController
{

    /**
     * Show the general settings app tab
     */
    public function show_app_tab( Request $request, Response $response )
    {

        $tab = "app";
        $link = 'admin.php?page=dt_home&tab=';
        $page_title = "Home Settings";

        $data = $this->get_all_apps_data();

        return view( "settings/app", compact( 'tab', 'link', 'page_title', 'data' ) );
    }

    protected function get_all_apps_data()
    {
        // Get the apps array from the option
        $apps_array = get_option( 'dt_home_apps', [] );

        // Sort the array based on the 'sort' key
        usort($apps_array, function ( $a, $b ) {
            return $a['sort'] - $b['sort'];
        });

        return $apps_array;
    }


    public function create_app( Request $request, Response $response )
    {
        $tab = "app";
        $link = 'admin.php?page=dt_home&tab=';
        $page_title = "Home Settings";
        $svg_service = new SVGIconService( get_template_directory() . '/dt-assets/images/' );
        $svg_icon_urls = $svg_service->get_svg_icon_urls();

        return view( "settings/create", compact( 'tab', 'link', 'page_title', 'svg_icon_urls' ) );
    }

    public function store( Request $request, Response $response )
    {
        // Retrieve form data
        $name = $request->input( 'name' );
        $type = $request->input( 'type' );
        $icon = $request->input( 'icon' );
        $url = $request->input( 'url' );
        $slug = $request->input( 'slug' );
        $sort = $request->input( 'sort' );
        $is_hidden = $request->input( 'is_hidden' );

        // Prepare the data to be stored
        $app_data = [
            'name' => $name,
            'type' => $type,
            'icon' => $icon,
            'url' => $url,
            'sort' => $sort,
            'slug' => $slug,
            'is_hidden' => $is_hidden,
        ];

        // Get the existing apps array
        $apps_array = get_option( 'dt_home_apps', [] ); // Default to an empty array if the option does not exist

        // Generate a unique ID for the new app
        $next_id = 1;
        foreach ( $apps_array as $app ) {
            if ( isset( $app['id'] ) && $app['id'] >= $next_id ) {
                $next_id = $app['id'] + 1;
            }
        }

        $app_data['id'] = $next_id; // Add the ID to the new app data

        // Append new app data to the array
        $apps_array[] = $app_data;

        // Save the updated array back to the option
        update_option( 'dt_home_apps', $apps_array );

        $response = new RedirectResponse( 'admin.php?page=dt_home&tab=app&updated=true', 302 );

        return $response;
    }

    public function unhide( $id )
    {
        // Retrieve the existing array of apps
        $apps_array = get_option( 'dt_home_apps', [] );

        // Find the app with the specified ID and update its 'is_hidden' status
        foreach ( $apps_array as $key => $app ) {
            if ( isset( $app['id'] ) && $app['id'] == $id ) {
                $apps_array[$key]['is_hidden'] = 0; // Set 'is_hidden' to 0 (unhide)
                break; // Exit the loop once the app is found and updated
            }
        }

        // Save the updated array back to the option
        update_option( 'dt_home_apps', $apps_array );

        // Redirect to the page with a success message
        $response = new RedirectResponse( 'admin.php?page=dt_home&tab=app&updated=true', 302 );

        return $response;
    }

    public function hide( $id )
    {
        // Retrieve the existing array of apps
        $apps_array = get_option( 'dt_home_apps', [] );

        // Find the app with the specified ID and update its 'is_hidden' status
        foreach ( $apps_array as $key => $app ) {
            if ( isset( $app['id'] ) && $app['id'] == $id ) {
                $apps_array[$key]['is_hidden'] = 1; // Set 'is_hidden' to 1 (hide)
                break; // Exit the loop once the app is found and updated
            }
        }

        // Save the updated array back to the option
        update_option( 'dt_home_apps', $apps_array );

        // Redirect to the page with a success message
        $response = new RedirectResponse( 'admin.php?page=dt_home&tab=app&updated=true', 302 );

        return $response;
    }

    public function up( $id )
    {
        // Retrieve the existing array of apps
        $apps_array = get_option( 'dt_home_apps', [] );

        // Find the index of the app and its current sort value
        $current_index = null;
        $current_sort = null;
        foreach ( $apps_array as $key => $app ) {
            if ( $app['id'] == $id ) {
                $current_index = $key;
                $current_sort = $app['sort'];
                break;
            }
        }

        // Only proceed if the app was found and it's not already at the top
        if ( $current_index !== null && $current_sort > 1 ) {
            // Adjust the sort values
            foreach ( $apps_array as $key => &$app ) {
                if ( $app['sort'] == $current_sort - 1 ) {
                    // Increment the sort value of the app that's currently one position above
                    $app['sort']++;
                }
            }
            // Decrement the sort value of the current app
            $apps_array[$current_index]['sort']--;

            // Re-sort the array
            usort($apps_array, function ( $a, $b ) {
                return $a['sort'] - $b['sort'];
            });

            // Save the updated array back to the option
            update_option( 'dt_home_apps', $apps_array );
        }

        // Redirect to the page with a success message
        $response = new RedirectResponse( 'admin.php?page=dt_home&tab=app&updated=true', 302 );

        return $response;
    }

    public function down( $id )
    {
        // Retrieve the existing array of apps
        $apps_array = get_option( 'dt_home_apps', [] );

        // Find the index of the app and its current sort value
        $current_index = null;
        $current_sort = null;
        foreach ( $apps_array as $key => $app ) {
            if ( $app['id'] == $id ) {
                $current_index = $key;
                $current_sort = $app['sort'];
                break;
            }
        }

        // Determine the maximum sort value
        $max_sort = count( $apps_array );

        // Only proceed if the app was found and it's not already at the bottom
        if ( $current_index !== null && $current_sort < $max_sort ) {
            // Adjust the sort values
            foreach ( $apps_array as $key => &$app ) {
                if ( $app['sort'] == $current_sort + 1 ) {
                    // Decrement the sort value of the app that's currently one position below
                    $app['sort']--;
                }
            }
            // Increment the sort value of the current app
            $apps_array[$current_index]['sort']++;

            // Re-sort the array
            usort($apps_array, function ( $a, $b ) {
                return $a['sort'] - $b['sort'];
            });

            // Save the updated array back to the option
            update_option( 'dt_home_apps', $apps_array );
        }

        // Redirect to the page with a success message
        $response = new RedirectResponse( 'admin.php?page=dt_home&tab=app&updated=true', 302 );

        return $response;
    }

    public function update( Request $request, Response $response )
    {
        if ( isset( $_POST['submit'] ) ) {

            $name = $request->input( 'name' );
            $type = $request->input( 'type' );
            $icon_url = $request->input( 'icon' );
            $url = $request->input( 'url' );
            $sort = $request->input( 'sort' );
            $slug = $request->input( 'slug' );
            $is_hidden = $request->input( 'is_hidden' );

            // Get the ID of the item being edited
            $edit_id = $request->input( 'edit_id' );

            // Retrieve the existing array of apps
            $apps_array = get_option( 'dt_home_apps', [] );

            // Find and update the app in the array
            foreach ( $apps_array as $key => $app ) {
                if ( $app['id'] == $edit_id ) {
                    $apps_array[$key] = [
                        'id' => $edit_id, // Keep the ID unchanged
                        'name' => $name,
                        'type' => $type,
                        'icon' => $icon_url,
                        'url' => $url,
                        'slug' => $slug,
                        'sort' => $sort,
                        'is_hidden' => $is_hidden,
                    ];
                    break; // Stop the loop once the app is found and updated
                }
            }

            // Save the updated array back to the option
            update_option( 'dt_home_apps', $apps_array );

            // Redirect to the page with a success message
            $response = new RedirectResponse( 'admin.php?page=dt_home&tab=app&updated=true', 302 );

            return $response;
        }
    }

    public function edit_app( $id )
    {
        $edit_id = isset( $id ) ? intval( $id ) : 0;
        $svg_service = new SVGIconService( get_template_directory() . '/dt-assets/images/' );
        $svg_icon_urls = $svg_service->get_svg_icon_urls();

        if ( $edit_id ) {
            // Retrieve the existing data based on $edit_id
            $existing_data = $this->get_data_by_id( $edit_id );

            $tab = "app";
            $link = 'admin.php?page=dt_home&tab=';
            $page_title = "Home Settings";

            if ( $existing_data ) {
                // Load the edit form view and pass the existing data
                return view( "settings/edit", compact( 'existing_data', 'link', 'tab', 'page_title', 'svg_icon_urls' ) );
            }
        }
    }

    protected function get_data_by_id( $id )
    {
        $apps_array = get_option( 'dt_home_apps', [] );

        foreach ( $apps_array as $app ) {
            if ( isset( $app['id'] ) && $app['id'] == $id ) {
                return $app;
            }
        }

        return null; // Return null if no app is found with the given ID
    }
}
