<?php

namespace DT\Launcher\Controllers\MagicLink;

use DT\Launcher\Illuminate\Http\Request;
use DT\Launcher\Illuminate\Http\Response;
use function DT\Launcher\magic_url;
use function DT\Launcher\template;

class HomeController
{
    public function show(Request $request, Response $response, $key)
    {
        $user = wp_get_current_user();
        $subpage_url = magic_url('subpage', $key);

        $apps_array = get_option('dt_launcher_apps', []);
        $data = json_encode($apps_array);

        return template('index', compact(
            'user',
            'subpage_url',
            'data'
        ));
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
