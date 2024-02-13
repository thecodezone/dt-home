<?php

namespace DT\Launcher\Controllers\MagicLink;

use DT\Launcher\Illuminate\Http\Request;
use DT\Launcher\Illuminate\Http\Response;
use function DT\Launcher\redirect;

class ShareController
{
    public function show(Request $request, Response $response, $key)
    {

        $contact = \Disciple_Tools_Users::get_contact_for_user(get_current_user_id());

        if (!isset($_COOKIE['dt_home_share'])) {
            setcookie('dt_home_share', $contact, time() + (86400 * 30), "/");
        }

        return redirect('/login');
    }

    public function data(Request $request, Response $response, $key)
    {
        $user = wp_get_current_user();
        $data = [
            'user_login' => $user->user_login,
        ];
        $response->setContent($data);

        return $response;
    }
}
