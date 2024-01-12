<?php

namespace DT\Launcher\Controllers\Admin;

use DT\Launcher\Illuminate\Http\RedirectResponse;
use DT\Launcher\Illuminate\Http\Request;
use DT\Launcher\Illuminate\Http\Response;
use function DT\Launcher\view;

class AppSettingsController
{

    /**
     * Show the general settings app tab
     */
    public function show_app_tab(Request $request, Response $response)
    {
        global $wpdb;

        $tab = "app";
        $link = 'admin.php?page=dt_launcher&tab=';
        $page_title = "Launcher Settings";

        // Fetch data from the wp_apps table
        //$table_name = $wpdb->prefix . 'apps'; // Replace with your actual table name
        //$data = $wpdb->get_results("SELECT * FROM $table_name ORDER BY sort ASC", ARRAY_A);

        $data = $this->get_all_apps_data();

        return view("settings/app", compact('tab', 'link', 'page_title', 'data'));
    }

    public function get_all_apps_data()
    {
        // Get the apps array from the option
        $apps_array = get_option('dt_launcher_apps', array()); // Default to an empty array if the option does not exist

        // Sort the array based on the 'sort' key
        usort($apps_array, function ($a, $b) {
            return $a['sort'] - $b['sort'];
        });

        return $apps_array;
    }


    public function create_app(Request $request, Response $response)
    {
        $tab = "app";
        $link = 'admin.php?page=dt_launcher&tab=';
        $page_title = "Launcher Settings";

        return view("settings/create", compact('tab', 'link', 'page_title'));
    }

    public function store(Request $request, Response $response)
    {
        // Retrieve form data
        $name = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : '';
        $type = isset($_POST['type']) ? sanitize_text_field(wp_unslash($_POST['type'])) : '';
        $url = isset($_POST['url']) ? esc_url(wp_unslash($_POST['url'])) : '';
        $sort = isset($_POST['sort']) ? intval($_POST['sort']) : 0;
        $is_hidden = isset($_POST['is_hidden']) ? 1 : 0;
        $icon = isset($_POST['icon']) ? sanitize_text_field(wp_unslash($_POST['icon'])) : '';

        // Prepare the data to be stored
        $app_data = array(
            'name' => $name,
            'type' => $type,
            'icon' => $icon,
            'url' => $url,
            'sort' => $sort,
            'is_hidden' => $is_hidden,
        );

        // Get the existing apps array
        $apps_array = get_option('dt_launcher_apps', array()); // Default to an empty array if the option does not exist

        // Generate a unique ID for the new app
        $next_id = 1;
        foreach ($apps_array as $app) {
            if (isset($app['id']) && $app['id'] >= $next_id) {
                $next_id = $app['id'] + 1;
            }
        }

        $app_data['id'] = $next_id; // Add the ID to the new app data

        // Append new app data to the array
        $apps_array[] = $app_data;

        // Save the updated array back to the option
        update_option('dt_launcher_apps', $apps_array);

        $response = new RedirectResponse('admin.php?page=dt_launcher&tab=app&updated=true', 302);
        return $response;
    }


    public function edit_app($id)
    {
        $edit_id = isset($id) ? intval($id) : 0;

        if ($edit_id) {
            // Retrieve the existing data based on $edit_id
            $existing_data = $this->get_data_by_id($edit_id);

            $tab = "app";
            $link = 'admin.php?page=dt_launcher&tab=';
            $page_title = "Launcher Settings";

            if ($existing_data) {
                // Load the edit form view and pass the existing data
                return view("settings/edit", compact('existing_data', 'link', 'tab', 'page_title'));
            }
        }

        // Handle the case where no valid ID is provided or the app is not found
        // Redirect to a default page or show an error message
    }


    public function get_data_by_id($id)
    {
        $apps_array = get_option('dt_launcher_apps', array());
        foreach ($apps_array as $app) {
            if (isset($app['id']) && $app['id'] == $id) {
                return $app;
            }
        }
        return null; // Return null if no app is found with the given ID
    }

    public function unhide($id)
    {
        // Retrieve the existing array of apps
        $apps_array = get_option('dt_launcher_apps', array());

        // Find the app with the specified ID and update its 'is_hidden' status
        foreach ($apps_array as $key => $app) {
            if (isset($app['id']) && $app['id'] == $id) {
                $apps_array[$key]['is_hidden'] = 0; // Set 'is_hidden' to 0 (unhide)
                break; // Exit the loop once the app is found and updated
            }
        }

        // Save the updated array back to the option
        update_option('dt_launcher_apps', $apps_array);

        // Redirect to the page with a success message
        $response = new RedirectResponse('admin.php?page=dt_launcher&tab=app&updated=true', 302);
        return $response;
    }

    public function hide($id)
    {
        // Retrieve the existing array of apps
        $apps_array = get_option('dt_launcher_apps', array());

        // Find the app with the specified ID and update its 'is_hidden' status
        foreach ($apps_array as $key => $app) {
            if (isset($app['id']) && $app['id'] == $id) {
                $apps_array[$key]['is_hidden'] = 1; // Set 'is_hidden' to 1 (hide)
                break; // Exit the loop once the app is found and updated
            }
        }

        // Save the updated array back to the option
        update_option('dt_launcher_apps', $apps_array);

        // Redirect to the page with a success message
        $response = new RedirectResponse('admin.php?page=dt_launcher&tab=app&updated=true', 302);
        return $response;
    }

    public function up($id)
    {
        // Retrieve the existing array of apps
        $apps_array = get_option('dt_launcher_apps', array());

        // Find the index of the app and its current sort value
        $current_index = null;
        $current_sort = null;
        foreach ($apps_array as $key => $app) {
            if ($app['id'] == $id) {
                $current_index = $key;
                $current_sort = $app['sort'];
                break;
            }
        }

        // Only proceed if the app was found and it's not already at the top
        if ($current_index !== null && $current_sort > 1) {
            // Adjust the sort values
            foreach ($apps_array as $key => &$app) {
                if ($app['sort'] == $current_sort - 1) {
                    // Increment the sort value of the app that's currently one position above
                    $app['sort']++;
                }
            }
            // Decrement the sort value of the current app
            $apps_array[$current_index]['sort']--;

            // Re-sort the array
            usort($apps_array, function ($a, $b) {
                return $a['sort'] - $b['sort'];
            });

            // Save the updated array back to the option
            update_option('dt_launcher_apps', $apps_array);
        }

        // Redirect to the page with a success message
        $response = new RedirectResponse('admin.php?page=dt_launcher&tab=app&updated=true', 302);
        return $response;
    }

    public function down($id)
    {
        // Retrieve the existing array of apps
        $apps_array = get_option('dt_launcher_apps', array());

        // Find the index of the app and its current sort value
        $current_index = null;
        $current_sort = null;
        foreach ($apps_array as $key => $app) {
            if ($app['id'] == $id) {
                $current_index = $key;
                $current_sort = $app['sort'];
                break;
            }
        }

        // Determine the maximum sort value
        $max_sort = count($apps_array);

        // Only proceed if the app was found and it's not already at the bottom
        if ($current_index !== null && $current_sort < $max_sort) {
            // Adjust the sort values
            foreach ($apps_array as $key => &$app) {
                if ($app['sort'] == $current_sort + 1) {
                    // Decrement the sort value of the app that's currently one position below
                    $app['sort']--;
                }
            }
            // Increment the sort value of the current app
            $apps_array[$current_index]['sort']++;

            // Re-sort the array
            usort($apps_array, function ($a, $b) {
                return $a['sort'] - $b['sort'];
            });

            // Save the updated array back to the option
            update_option('dt_launcher_apps', $apps_array);
        }

        // Redirect to the page with a success message
        $response = new RedirectResponse('admin.php?page=dt_launcher&tab=app&updated=true', 302);
        return $response;
    }


    public function update(Request $request, Response $response)
    {
        if (isset($_POST['submit'])) {
            // Sanitize and validate your form data
            $name = sanitize_text_field(wp_unslash($_POST['name']));
            $type = sanitize_text_field(wp_unslash($_POST['type']));
            $url = isset($_POST['url']) ? esc_url(wp_unslash($_POST['url'])) : '';
            $sort = isset($_POST['sort']) ? intval($_POST['sort']) : 0;
            $is_hidden = isset($_POST['is_hidden']) ? 1 : 0; // Checkbox value
            $icon_url = sanitize_text_field(wp_unslash($_POST['icon']));

            // Get the ID of the item being edited
            $edit_id = isset($_POST['edit_id']) ? intval($_POST['edit_id']) : 0;

            // Retrieve the existing array of apps
            $apps_array = get_option('dt_launcher_apps', array());

            // Find and update the app in the array
            foreach ($apps_array as $key => $app) {
                if ($app['id'] == $edit_id) {
                    $apps_array[$key] = array(
                        'id' => $edit_id, // Keep the ID unchanged
                        'name' => $name,
                        'type' => $type,
                        'icon' => $icon_url,
                        'url' => $url,
                        'sort' => $sort,
                        'is_hidden' => $is_hidden,
                    );
                    break; // Stop the loop once the app is found and updated
                }
            }

            // Save the updated array back to the option
            update_option('dt_launcher_apps', $apps_array);

            // Redirect to the page with a success message
            $response = new RedirectResponse('admin.php?page=dt_launcher&tab=app&updated=true', 302);
            return $response;
        }

        // Handle the case where the form is not submitted or the ID is not provided
        // Redirect to a default page or show an error message
    }
}
