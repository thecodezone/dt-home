<?php

namespace DT\Home\Controllers\Admin;

use DT\Home\GuzzleHttp\Psr7\ServerRequest as Request;
use DT\Home\Psr\Http\Message\ResponseInterface;
use DT\Home\Services\Apps;
use DT\Home\Services\SVGIconService;
use DT\Home\Sources\SettingsApps;
use function DT\Home\extract_request_input;
use function DT\Home\redirect;
use function DT\Home\response;
use function DT\Home\set_plugin_option;
use function DT\Home\view;

class AppSettingsController {

    private Apps $apps;
    private SettingsApps $settings_apps;

    public function __construct( Apps $apps, SettingsApps $source )
    {
        $this->apps = $apps;
        $this->settings_apps = $source;
    }

    /**
     * Show the apps settings app tab.
     *
     * @return ResponseInterface
     */
    public function show() {

        $tab        = "app";
        $link       = 'admin.php?page=dt_home&tab=';
        $page_title = "Home Settings";

        $data = $this->apps->from( 'settings' ); // or $this->settings_apps->merged() or SettingsApps::class

        return view( "settings/app", compact( 'tab', 'link', 'page_title', 'data' ) );
    }

    /**
     * Show the available apps tab.
     * @return ResponseInterface
     */

    public function show_available_apps() {

        $tab        = "app";
        $link       = 'admin.php?page=dt_home&tab=';
        $page_title = "Home Settings";

        $data = $this->settings_apps->deleted();

        return view( "settings/available-apps", compact( 'tab', 'link', 'page_title', 'data' ) );
    }

    /**
     * Show the form to create a new app.
     *
     * @return ResponseInterface
     */
    public function create() {
        $tab         = "app";
        $link        = 'admin.php?page=dt_home&tab=';
        $page_title  = "Home Settings";

        return view( "settings/create", compact( 'tab', 'link', 'page_title' ) );
    }

    /**
     * Store a new app in the database.
     *
     * @param Request $request The request object containing the form data.
     *
     * @return ResponseInterface
     */
    public function store( Request $request ) {
        // Retrieve form data
        $input = extract_request_input( $request );

        $name            = sanitize_text_field( $input['name'] ?? '' );
        $type            = sanitize_text_field( $input['type'] ?? '' );
        $creation_type   = sanitize_text_field( $input['creation_type'] ?? '' );
        $icon            = sanitize_text_field( $input['icon'] ?? '' );
        $url             = sanitize_text_field( $input['url'] ?? '' );
        $slug            = sanitize_text_field( $input['slug'] ?? '' );
        $sort            = sanitize_text_field( $input['sort'] ?? '' );
        $is_hidden       = filter_var( $input['is_hidden'] ?? '0', FILTER_SANITIZE_NUMBER_INT );
        $open_in_new_tab = filter_var( $input['open_in_new_tab'] ?? '0', FILTER_SANITIZE_NUMBER_INT );

        // Prepare the data to be stored
        $app_data = [
            'name'            => $name,
            'type'            => $type,
            'creation_type'   => $creation_type,
            'icon'            => $icon,
            'url'             => $url,
            'sort'            => $sort,
            'slug'            => $slug,
            'is_hidden'       => $is_hidden == "1" ? 1 : 0,
            'open_in_new_tab' => $open_in_new_tab,
        ];

        // Get the existing apps array
        $apps_array = $this->apps->from( 'settings' ); // Default to an empty array if the option does not exist

        // Avoid duplicate slugs and append unique counter if required.
        $dup_apps = array_filter( $apps_array, function ( $app ) use ( $app_data ) {

            // Check for identical matches, as well as for previously set slugs, with appended counts.
            return ( isset( $app['slug'] ) && ( ( $app['slug'] === $app_data['slug'] ) || ( substr( $app['slug'], 0, strlen( $app_data['slug'] . '_' ) ) === ( $app_data['slug'] . '_' ) ) ) );
        } );
        if ( count( $dup_apps ) > 0 ) {
            $app_data['slug'] .= '_' . ( count( $dup_apps ) + 1 );
        }

        // Append new app data to the array
        $apps_array[] = $app_data;

        $this->settings_apps->save( $apps_array );

        return redirect( 'admin.php?page=dt_home&tab=app&updated=true' );
    }

    /**
     * Unhide an app.
     *
     * @param Request $request The request object.
     * @param mixed $params Additional parameters.
     *
     * @return ResponseInterface
     */
    public function unhide( Request $request, $params ) {
        $result = $this->settings_apps->unhide( $params['slug'] );

        return redirect( 'admin.php?page=dt_home&tab=app&updated=' . ( $result['is_hidden'] ? 'false' : 'true' ) );
    }

    /**
     * Hide an app based on its slug.
     *
     * @param Request $request The request instance.
     * @param array $params The route parameters.
     *
     * @return ResponseInterface The redirect response.
     */
    public function hide( Request $request, $params ) {
        $result = $this->settings_apps->hide( $params['slug'] );

        return redirect( 'admin.php?page=dt_home&tab=app&updated=' . ( $result['is_hidden'] ? 'true' : 'false' ) );
    }

    /**
     * Updates the sort order of an app.
     *
     * @param Request $request The request instance.
     * @param array $params The route parameters.
     *
     * @return ResponseInterface The RedirectResponse instance.
     */

    public function up( Request $request, $params ) {
        return redirect( 'admin.php?page=dt_home&tab=app&updated=' . ( $this->move( 'up', $params ) ? 'true' : 'false' ) );
    }

    /**
     * Move an app down in the list of apps.
     *
     * @param Request $request The request instance.
     * @param array $params The route parameters.
     *
     * @return ResponseInterface The RedirectResponse instance.
     */
    public function down( Request $request, $params ) {
        return redirect( 'admin.php?page=dt_home&tab=app&updated=' . ( $this->move( 'down', $params ) ? 'true' : 'false' ) );
    }

    /**
     * Update an app.
     *
     * @param Request $request The request instance.
     * @param array $params The route parameters.
     *
     * @return ResponseInterface
     */
    public function update( Request $request, $params ) {

        $slug            = $params['slug'] ?? '';
        $input           = extract_request_input( $request );
        $name            = sanitize_text_field( $input['name'] ?? '' );
        $type            = sanitize_text_field( $input['type'] ?? '' );
        $creation_type   = sanitize_text_field( $input['creation_type'] ?? '' );
        $icon_url        = sanitize_text_field( $input['icon'] ?? '' );
        $url             = sanitize_text_field( $input['url'] ?? '' );
        $sort            = sanitize_text_field( $input['sort'] ?? '' );
        $new_slug        = sanitize_text_field( $input['slug'] ?? '' );
        $is_hidden       = filter_var( $input['is_hidden'] ?? '0', FILTER_SANITIZE_NUMBER_INT );
        $open_in_new_tab = filter_var( $input['open_in_new_tab'] ?? '0', FILTER_SANITIZE_NUMBER_INT );

        // Retrieve the existing array of apps
        $apps_array       = $this->apps->from( $this->settings_apps );

        // Find and update the app in the array
        foreach ( $apps_array as $key => $app ) {
            if ( $app['slug'] == $slug ) {
                $apps_array[ $key ] = [
                    'name'            => $name,
                    'type'            => $type,
                    'creation_type'   => $creation_type,
                    'icon'            => $icon_url,
                    'url'             => $url,
                    'slug'            => $new_slug,
                    'sort'            => $sort,
                    'is_hidden'       => $is_hidden == "1" ? 1 : 0,
                    'open_in_new_tab' => $open_in_new_tab,
                ];
                break; // Stop the loop once the app is found and updated
            }
        }

        $this->settings_apps->save( $apps_array );

        return redirect( 'admin.php?page=dt_home&tab=app&updated=true' );
    }

    /**
     * Show the form to edit an existing app.
     *
     * @param Request $request The request instance.
     * @param array $params The route parameters.
     *
     * @return ResponseInterface
     */
    public function edit( Request $request, $params ) {
        $slug        = $params['slug'] ?? '';

        $existing_data = $this->apps->find( $slug );

        $tab        = "app";
        $link       = 'admin.php?page=dt_home&tab=';
        $page_title = "Home Settings";

        if ( ! $existing_data ) {
            return response( __( 'App not found', 'dt-home' ), 404 );
        }

        // Load the edit form view and pass the existing data
        return view( "settings/edit", compact( 'existing_data', 'link', 'tab', 'page_title' ) );
    }


    /**
     * Delete an app by its slug.
     *
     * @param Request $request The request instance.
     * @param array $params The route parameters.
     *
     * @return ResponseInterface
     */
    public function delete( Request $request, $params ) {
        $slug = $params['slug'] ?? '';

        $this->settings_apps->destroy( $slug );

        return redirect( 'admin.php?page=dt_home&tab=app&updated=true' );
    }

    /** Soft delete an app by its slug.
     * This function marks an app as soft deleted based on its slug.
     *
     * @param Request $request The request instance.
     * @param array $params The route parameters.
     *
     * @return ResponseInterface
     */
    public function soft_delete_app( Request $request, $params ) {
        $slug = $params['slug'] ?? '';

        $is_deleted = $this->settings_apps->delete( $slug );

        return redirect( 'admin.php?page=dt_home&tab=app&updated=' . ( $is_deleted ? 'true' : 'false' ) );
    }

    /**
     * Restore an app by its slug.
     *
     * This function restores a soft deleted app based on its slug.
     *
     * @param Request $request The request instance.
     * @param array $params The route parameters.
     *
     * @return ResponseInterface
     */
    public function restore_app( Request $request, $params ) {
        $slug = $params['slug'] ?? '';

        if ( empty( $slug ) ) {
            return redirect( 'admin.php?page=dt_home&tab=app&updated=false' );
        }

        // Retrieve the existing array of apps
        $apps_array = $this->apps
            ->settings_apps
            ->deleted();

        // Find the app with the specified slug and restore it
        foreach ( $apps_array as $key => $app ) {
            if ( isset( $app['slug'] ) && $app['slug'] == $slug ) {
                $apps_array[ $key ]['is_deleted'] = false; // Restore the app
                break; // Exit the loop once the app is found and restored
            }
        }

        // Save the updated array back to the option
        $this->apps->settings_apps->save( $apps_array );

        // Redirect to the page with a success message
        return redirect( 'admin.php?page=dt_home&tab=app&action=available_app&updated=true' );
    }



    /**
     * Handle directional movement of apps within
     * admin list.
     *
     * @param string $direction The direction (up/down).
     * @param array $params The route parameters.
     *
     * @return bool Boolean flag indicating whether directional change was successful.
     */

    public function move( $direction, $params ): bool {
        $slug = $params['slug'] ?? '';
        if ( empty( $slug ) ) {
            return false;
        }

        // Retrieve the existing array of apps
        $apps_array = $this->apps->from( $this->settings_apps );

        // Find the index of the app and its current sort value
        $current_index = null;
        $current_sort  = null;
        foreach ( $apps_array as $key => $app ) {
            if ( $app['slug'] == $slug ) {
                $current_index = $key;
                $current_sort  = (int) $app['sort'];
                break;
            }
        }

        $save_updates = true;

        // Determine the maximum sort value
        $max_sort = count( $apps_array );

        switch ( $direction ) {
            case 'up':

                // Adjust the sort values
                foreach ( $apps_array as $key => &$app ) {
                    if ( $app['sort'] == $current_sort - 1 ) {
                        // Increment the sort value of the app that's currently one position above
                        $app['sort']++;
                    }
                }

                // Decrement the sort value of the current app
                if ( $current_sort > 0 ) {
                    $apps_array[ $current_index ]['sort']--;
                }

                break;

            case 'down':

                // Only proceed if the app was found and it's not already at the bottom
                if ( $current_index !== null && $current_sort < $max_sort ) {
                    // Adjust the sort values
                    foreach ( $apps_array as $key => &$app ) {
                        if ( $app['sort'] == (int) $current_sort + 1 ) {
                            // Decrement the sort value of the app that's currently one position below
                            $app['sort']--;
                        }
                    }
                    // Increment the sort value of the current app
                    $apps_array[ $current_index ]['sort']++;

                } else {
                    $save_updates = false;
                }

                break;
        }

        // Determine if changes are to be persisted.
        if ( $save_updates ) {

            // Normalize the sort values to ensure they are positive and sequential
            usort( $apps_array, function ( $a, $b ) {
                return (int) $a['sort'] - (int) $b['sort'];
            } );

            foreach ( $apps_array as $key => &$app ) {
                $app['sort'] = $key;
            }

            // Save the updated array back to the option
            set_plugin_option( 'apps', $apps_array );
        }

        return true;
    }
}
