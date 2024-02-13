<?php

namespace DT\Launcher\Controllers;

use DT\Launcher\Illuminate\Http\Request;
use DT\Launcher\Illuminate\Http\Response;
use function DT\Launcher\redirect;
use function DT\Launcher\template;

class LoginController
{

    /**
     * Process the login form
     */
    public function login_process(Request $request, Response $response)
    {
        global $errors;

        $username = $request->input('username' ?? '');
        $password = $request->input('password' ?? '');

        $user = wp_authenticate($username, $password);

        if (is_wp_error($user)) {
            //phpcs:ignore
            $errors = $user;
            $error = $errors->get_error_message();
            $error = apply_filters('login_errors', $error);

            //If the error links to lost password, inject the 3/3rds redirect
            $error = str_replace('?action=lostpassword', '?action=lostpassword?&redirect_to=/', $error);

            return $this->login(['error' => $error, 'username' => $username, 'password' => $password]);
        }

        wp_set_auth_cookie($user->ID);

        if (!$user) {
            return $this->login(['error' => esc_html_e('An unexpected error has occurred.', 'dt_home')]);
        }

        wp_set_current_user($user->ID);

        return redirect('/launcher');
    }

    /**
     * Show the login template
     */
    public function login($params = [])
    {
        $register_url = '/launcher/register';
        $form_action = '/launcher/login';
        $username = $params['username'] ?? '';
        $password = $params['password'] ?? '';
        $error = $params['error'] ?? '';
        $logo_path = get_site_url() . '/wp-content/plugins/dt-home/resources/img/logo-color.png';
        $reset_url = wp_lostpassword_url($this->get_link_url());

        return template('auth/login', [
            'register_url' => $register_url,
            'form_action' => $form_action,
            'username' => $username,
            'password' => $password,
            'logo_path' => $logo_path,
            'reset_url' => $reset_url,
            'error' => $error
        ]);

    }

    public function logout($params = [])
    {
        wp_logout();
        return redirect('/launcher/login');
        exit;
    }

    public function get_link_url()
    {
        return get_site_url(null, 'launcher');
    }
}
