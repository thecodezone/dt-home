<?php

namespace DT\Launcher\Controllers\Admin;

use DT\Launcher\Illuminate\Http\RedirectResponse;
use DT\Launcher\Illuminate\Http\Request;
use DT\Launcher\Illuminate\Http\Response;
use function DT\Launcher\view;

class GeneralSettingsController
{
    /**
     * Show the general settings admin tab
     */
    public function show(Request $request, Response $response)
    {
        $tab = "general";
        $link = 'admin.php?page=dt_launcher&tab=';
        $page_title = "Launcher Settings";

        return view("settings/general", compact('tab', 'link', 'page_title'));
    }

    public function show_app_tab(Request $request, Response $response)
    {

        if (!current_user_can('manage_dt')) {
            wp_die('You do not have sufficient permissions to access this page.');
        }

        global $wpdb;

        $tab = "app";
        $link = 'admin.php?page=dt_launcher&tab=';
        $page_title = "Launcher Settings";

        // Fetch data from the wp_apps table
        $table_name = $wpdb->prefix . 'apps'; // Replace with your actual table name
        $data = $wpdb->get_results("SELECT * FROM $table_name ORDER BY sort ASC", ARRAY_A);

        return view("settings/app", compact('tab', 'link', 'page_title', 'data'));
    }

    public function create_app(Request $request, Response $response)
    {

        if (!current_user_can('manage_dt')) { // manage dt is a permission that is specific to Disciple.Tools and allows admins, strategists and dispatchers into the wp-admin
            wp_die('You do not have sufficient permissions to access this page.');
        }

        $tab = "app";
        $link = 'admin.php?page=dt_launcher&tab=';
        $page_title = "Launcher Settings";

        return view("settings/create", compact('tab', 'link', 'page_title'));
    }

    public function store(Request $request, Response $response)
    {

        if (current_user_can('manage_dt')) {
            global $wpdb;

            $table_name = $wpdb->prefix . 'apps';

            // Retrieve form data
            $name = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : '';
            $type = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '';
            $url = isset($_POST['url']) ? esc_url(wp_unslash($_POST['url'])) : '';
            $sort = isset($_POST['sort']) ? intval($_POST['sort']) : 0;
            $is_hidden = isset($_POST['is_hidden']) ? 1 : 0;
            $icon = isset($_POST['icon']) ? sanitize_text_field($_POST['icon']) : '';

            // Insert data into the custom table
            $wpdb->insert(
                $table_name,
                array(
                    'name' => $name,
                    'type' => $type,
                    'icon' => $icon,
                    'url' => $url,
                    'sort' => $sort,
                    'is_hidden' => $is_hidden,
                )
            );

            $response = new RedirectResponse('admin.php?page=dt_launcher&tab=app&updated=true', 302); // Use the appropriate HTTP status code (e.g., 302 for Found)

            return $response;
        } else {
            wp_die('You do not have sufficient permissions.');
        }

    }

    public function edit_app($id)
    {

        $edit_id = isset($id) ? intval($id) : 0;

        if ($edit_id) {
            // Retrieve the existing data based on $edit_id
            $existing_data = $this->get_data_by_id($edit_id); // Implement this function

            $tab = "app";
            $link = 'admin.php?page=disciple_tools_autolink&tab=';
            $page_title = "Autolink Settings";

            if ($existing_data) {
                // Load the edit form view and pass the existing data
                return view("settings/edit", compact('existing_data', 'link', 'tab', 'page_title'));
            }
        }
    }

    public function get_data_by_id($id)
    {
        // Implement the logic to retrieve data based on the provided ID
        global $wpdb;

        $table_name = $wpdb->prefix . 'apps'; // Replace with your actual table name

        $result = $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id),
            ARRAY_A
        );

        return $result;
    }

    function unhide($id)
    {

        // Check if the form is submitted and the nonce is valid
        $is_hidden = 0; // Checkbox value

        // Update the data in the database
        $updated_data = array(
            'is_hidden' => $is_hidden,
        );

        global $wpdb;
        $wpdb->update('wp_apps', $updated_data, array('id' => $id));

        // Redirect or display a success message as needed
        $response = new RedirectResponse('admin.php?page=dt_launcher&tab=app&updated=true', 302); // Use the appropriate HTTP status code (e.g., 302 for Found)

        return $response;
    }

    function update(Request $request, Response $response)
    {

        // Check if the form is submitted and the nonce is valid
        if (isset($_POST['submit'])) {

            // Sanitize and validate your form data
            $name = sanitize_text_field($_POST['name']);
            //$type = sanitize_text_field( $_POST['type'] );
            $url = isset($_POST['url']) ? esc_url(wp_unslash($_POST['url'])) : '';
            $sort = intval($_POST['sort']);
            $is_hidden = isset($_POST['is_hidden']) ? 1 : 0; // Checkbox value
            $icon_url = sanitize_text_field($_POST['icon']);

            // Get the ID of the item being edited
            $edit_id = intval($_POST['edit_id']);

            // Update the data in the database
            $updated_data = array(
                'name' => $name,
                //'type' => $type,
                'icon' => $icon_url,
                'url' => $url,
                'sort' => $sort,
                'is_hidden' => $is_hidden,
            );

            global $wpdb;
            $wpdb->update('wp_apps', $updated_data, array('id' => $edit_id));

            $response = new RedirectResponse('admin.php?page=dt_launcher&tab=app&updated=true', 302); // Use the appropriate HTTP status code (e.g., 302 for Found)

            return $response;
        }
    }

    function hide($id)
    {

        $is_hidden = 1;

        // Update the data in the database
        $updated_data = array(
            'is_hidden' => $is_hidden,
        );

        global $wpdb;
        $wpdb->update('wp_apps', $updated_data, array('id' => $id));

        // Redirect or display a success message as needed
        $response = new RedirectResponse('admin.php?page=dt_launcher&tab=app&updated=true', 302); // Use the appropriate HTTP status code (e.g., 302 for Found)

        return $response;
    }

    /**
     * Submit the general settings admin tab form
     */
    public function update(Request $request, Response $response)
    {

        // Add the settings update code here

        return new RedirectResponse(302, admin_url('admin.php?page=dt_launcher&tab=general&updated=true'));

    }

    public function update_user_access_settings(Request $request, Response $response)
    {

        $require_user = isset($_POST['dt_launcher_require_login']) ? true : false;

        update_option('require_user', $require_user);

        $redirect_url = add_query_arg('message', 'updated', admin_url('admin.php?page=dt_launcher'));

        return new RedirectResponse($redirect_url);

    }

    function up($id)
    {

        $existing_data = $this->get_data_by_id($id);

        if ($existing_data['sort'] == 1) {
            $sort = $existing_data['sort'];
        } else {
            $sort = $existing_data['sort'] - 1;
        }

        // Update the data in the database
        $updated_data = array(
            'sort' => $sort,
        );

        global $wpdb;
        $wpdb->update('wp_apps', $updated_data, array('id' => $id));

        // Redirect or display a success message as needed
        $response = new RedirectResponse('admin.php?page=dt_launcher&tab=app&updated=true', 302); // Use the appropriate HTTP status code (e.g., 302 for Found)

        return $response;
    }

    function down($id)
    {

        $existing_data = $this->get_data_by_id($id);

        $sort = $existing_data['sort'] + 1;

        // Update the data in the database
        $updated_data = array(
            'sort' => $sort,
        );

        global $wpdb;
        $wpdb->update('wp_apps', $updated_data, array('id' => $id));

        // Redirect or display a success message as needed
        $response = new RedirectResponse('admin.php?page=dt_launcher&tab=app&updated=true', 302); // Use the appropriate HTTP status code (e.g., 302 for Found)

        return $response;
    }
}
