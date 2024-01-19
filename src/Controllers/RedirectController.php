<?php

namespace DT\Launcher\Controllers;


use Disciple_Tools_Users;
use DT\Launcher\Illuminate\Http\Request;
use DT\Launcher\Illuminate\Http\Response;
use DT_Magic_URL;
use function DT\Launcher\magic_url;
use function DT\Launcher\redirect;

class RedirectController
{

    /**
     * Redirects to the URL for the launcher app. This uses the
     * auth middleware, so the user will be redirected to
     * the login page if they are not logged in.
     *
     * @param Request $request The HTTP request object.
     * @param Response $response The HTTP response object.
     */
    public function show(Request $request, Response $response)
    {

        global $wpdb;

        $preference_key = 'dt-launcher-app';
        $meta_key = $wpdb->prefix . DT_Magic_URL::get_public_key_meta_key('launcher', 'app');

        if (!$this->is_activated()) {
            delete_user_meta(get_current_user_id(), $meta_key);
            delete_user_option(get_current_user_id(), $preference_key);

            add_user_meta(get_current_user_id(), $meta_key, DT_Magic_URL::create_unique_key());
            Disciple_Tools_Users::app_switch(get_current_user_id(), $preference_key);
        }

        return redirect(magic_url());
    }

    public function is_activated()
    {
        global $wpdb;
        $preference_key = 'dt-launcher-app';
        $meta_key = $wpdb->prefix . DT_Magic_URL::get_public_key_meta_key('launcher', 'app');
        $public = get_user_meta(get_current_user_id(), $meta_key, true);
        $secret = get_user_option($preference_key);

        if ($public === '' || $public === false || $public === '0' || $secret === '' || $secret === false || $secret === '0') {
            return false;
        }

        return true;
    }

}
