<?php

namespace DT\Home\Controllers\Admin;

use DT\Home\Illuminate\Http\RedirectResponse;
use DT\Home\Illuminate\Http\Request;
use DT\Home\Illuminate\Http\Response;
use DT\Home\Services\Apps;
use DT\Home\Services\SVGIconService;
use function DT\Home\container;
use function DT\Home\view;

class AppSettingsController
{

    /**
     * Show the general settings app tab.
     *
     * @param Request $request
     * @param Response $response
     *
     * @return mixed
     */
    public function show_app_tab( Request $request, Response $response )
    {

        $tab = "app";
        $link = 'admin.php?page=dt_home&tab=';
        $page_title = "Home Settings";

        $data = $this->get_all_apps_data();

        return view( "settings/app", compact( 'tab', 'link', 'page_title', 'data' ) );
    }

    /**
     * Get all apps data from the options and ensure default values.
     *
     * @return array
     */
    protected function get_all_apps_data()
    {
        $apps = container()->make( Apps::class );
        // Get the apps array from the option
        $apps_array = $apps->all();

        // Sort the array based on the 'sort' key
        usort($apps_array, function ( $a, $b ) {
            return $a['sort'] - $b['sort'];
        });

        $apps_array = array_map(function ( $app ) {
            return array_merge([
                'name' => '',
                'type' => 'webview',
                'icon' => '',
                'url' => '',
                'sort' => 0,
                'slug' => '',
                'is_hidden' => false,
            ], $app);
        }, $apps_array);


        return $apps_array;
    }

    /**
     * Show the form to create a new app.
     *
     * @param Request $request
     * @param Response $response
     *
     * @return mixed
     */
    public function create_app( Request $request, Response $response )
    {
        $tab = "app";
        $link = 'admin.php?page=dt_home&tab=';
        $page_title = "Home Settings";
        $svg_service = new SVGIconService( get_template_directory() . '/dt-assets/images/' );
        $svg_icon_urls = $svg_service->get_svg_icon_urls();

        return view( "settings/create", compact( 'tab', 'link', 'page_title', 'svg_icon_urls' ) );
    }

    /**
     * Store a new app.
     *
     * @param Request $request
     * @param Response $response
     *
     * @return RedirectResponse
     */
    public function store( Request $request, Response $response, Apps $apps )
    {
        // Retrieve form data
        $name = $request->input( 'name' );
        $type = $request->input( 'type' );
        $icon = $request->input( 'icon' );
        $url = $request->input( 'url' );
        $slug = $request->input( 'slug' );
        $sort = $request->input( 'sort' );
        $is_hidden = $request->input( 'is_hidden' );
        $open_in_new_tab = $request->input( 'open_in_new_tab' );


        // Prepare the data to be stored
        $app_data = [
            'name' => $name,
            'type' => $type,
            'icon' => $icon,
            'url' => $url,
            'sort' => $sort,
            'slug' => $slug,
            'is_hidden' => $is_hidden,
            'open_in_new_tab' => $open_in_new_tab,
        ];

        // Get the existing apps array
        $apps_array = $apps->all(); // Default to an empty array if the option does not exist

        // Append new app data to the array
        $apps_array[] = $app_data;

        // Save the updated array back to the option
        update_option( 'dt_home_apps', $apps_array );

        $response = new RedirectResponse( 'admin.php?page=dt_home&tab=app&updated=true', 302 );

        return $response;
    }

    /**
     * Unhide an app by ID.
     *
     * @param int $slug
     *
     * @return RedirectResponse
     */
    public function unhide( Apps $apps, $slug )
    {
        // Retrieve the existing array of apps
        $apps_array = $apps->all();

        // Find the app with the specified ID and update its 'is_hidden' status
        foreach ( $apps_array as $key => $app ) {
            if ( isset( $app['slug'] ) && $app['slug'] == $slug ) {
                $apps_array[$key]['is_hidden'] = 0;
                break;
            }
        }

        // Save the updated array back to the option
        update_option( 'dt_home_apps', $apps_array );

        // Redirect to the page with a success message
        $response = new RedirectResponse( 'admin.php?page=dt_home&tab=app&updated=true', 302 );

        return $response;
    }

    /**
     * Hide an app by ID.
     *
     * @param int $slug
     *
     * @return RedirectResponse
     */
    public function hide( Apps $apps, $slug )
    {
        // Retrieve the existing array of apps
        $apps_array = $apps->all();

        // Find the app with the specified ID and update its 'is_hidden' status
        foreach ( $apps_array as $key => $app ) {
            if ( isset( $app['slug'] ) && $app['slug'] == $slug ) {
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

    /**
     * Move an app up in the list by ID.
     *
     * @param int $slug
     *
     * @return RedirectResponse
     */
    public function up( Apps $apps, $slug )
    {
        // Retrieve the existing array of apps
        $apps_array = $apps->all();

        // Find the index of the app and its current sort value
        $current_index = null;
        $current_sort = null;
        foreach ( $apps_array as $key => $app ) {
            if ( $app['slug'] == $slug ) {
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

    /**
     * Move an app down in the list by ID.
     *
     * @param int $slug
     *
     * @return RedirectResponse
     */
    public function down( Apps $apps, $slug )
    {
        // Retrieve the existing array of apps
        $apps_array = $apps->all();

        // Find the index of the app and its current sort value
        $current_index = null;
        $current_sort = null;
        foreach ( $apps_array as $key => $app ) {
            if ( $app['slug'] == $slug ) {
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

    /**
     * Update an existing app.
     *
     * @param Request $request
     * @param Response $response
     *
     * @return RedirectResponse
     */
    public function update( Request $request, Response $response, Apps $apps, $slug )
    {
        $name = $request->input( 'name' );
        $type = $request->input( 'type' );
        $icon_url = $request->input( 'icon' );
        $url = $request->input( 'url' );
        $sort = $request->input( 'sort' );
        $new_slug = $request->input( 'slug' );
        $is_hidden = $request->input( 'is_hidden' );
        $open_in_new_tab = $request->input( 'open_in_new_tab' );

        // Retrieve the existing array of apps
        $apps_array = $apps->all();


        // Find and update the app in the array
        foreach ( $apps_array as $key => $app ) {
            if ( $app['slug'] == $slug ) {
                $apps_array[$key] = [
                    'name' => $name,
                    'type' => $type,
                    'icon' => $icon_url,
                    'url' => $url,
                    'slug' => $new_slug,
                    'sort' => $sort,
                    'is_hidden' => $is_hidden,
                    'open_in_new_tab' => $open_in_new_tab,
                ];
                break; // Stop the loop once the app is found and updated
            }
        }

        // Save the updated array back to the option
        update_option( 'dt_home_apps', $apps_array );

        // Redirect to the page with a success message
        return new RedirectResponse( 'admin.php?page=dt_home&tab=app&updated=true', 302 );
    }

    /**
     * Show the form to edit an existing app by ID.
     *
     * @param int $slug
     *
     * @return mixed
     */
    public function edit_app( Response $response, $slug )
    {
        $svg_service = new SVGIconService( get_template_directory() . '/dt-assets/images/' );
        $svg_icon_urls = $svg_service->get_svg_icon_urls();

        $existing_data = $this->get_data_by_slug( $slug );

        $tab = "app";
        $link = 'admin.php?page=dt_home&tab=';
        $page_title = "Home Settings";

        if ( !$existing_data ) {
            return $response->setStatusCode( 404 );
        }

        // Load the edit form view and pass the existing data
        return view( "settings/edit", compact( 'existing_data', 'link', 'tab', 'page_title', 'svg_icon_urls' ) );
    }

    /**
     * Get app data by ID.
     *
     * @param int $slug
     *
     * @return mixed
     */
    protected function get_data_by_slug( $slug )
    {
        $apps_array = container()->make( Apps::class )->all();

        foreach ( $apps_array as $app ) {
            if ( isset( $app['slug'] ) && $app['slug'] == $slug ) {
                return $app;
            }
        }

        return null; // Return null if no app is found with the given slug
    }

    /**
     * Delete an app by its slug.
     *
     * This function removes an app from the list of home apps based on its slug.
     *
     * @param string $slug The slug of the app to be deleted.
     * @return \Symfony\Component\HttpFoundation\RedirectResponse Redirects to the admin page with a success message.
     */
    public function delete_app( $slug )
    {

        // Retrieve the existing array of trainings
        $appss_array = get_option( 'dt_home_apps', [] );

        // Find the app with the specified ID and remove it from the array
        foreach ( $appss_array as $key => $app ) {
            if ( isset( $app['slug'] ) && $app['slug'] == $slug ) {
                unset( $appss_array[$key] ); // Remove the app from the array
                break; // Exit the loop once the app is found and removed
            }
        }

        // Save the updated array back to the option
        update_option( 'dt_home_apps', $appss_array );

        // Redirect to the page with a success message
        $response = new RedirectResponse( 'admin.php?page=dt_home&tab=app&updated=true', 302 );

        return $response;
    }
}
