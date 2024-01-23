<?php

namespace DT\Launcher\Controllers\MagicLink;

use DT\Launcher\Illuminate\Http\Request;
use DT\Launcher\Illuminate\Http\Response;
use function DT\Launcher\magic_url;
use function DT\Launcher\redirect;
use function DT\Launcher\template;
use function DT\Launcher\view;

class TrainingController
{
    public function show(Request $request, Response $response, $key)
    {

        $data = $this->get_all_trainings_data();

        return template( 'training', compact(
            'data'
        ) );
    }

    protected function get_all_trainings_data()
    {
        // Get the apps array from the option
        $trainings_array = get_option('dt_launcher_trainings', []);

        // Sort the array based on the 'sort' key
        usort($trainings_array, function ($a, $b) {
            return $a['sort'] - $b['sort'];
        });

        return $trainings_array;
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
