<?php

namespace DT\Home\Controllers\MagicLink;

use DT\Home\Illuminate\Http\Request;
use DT\Home\Illuminate\Http\Response;
use function DT\Home\magic_url;
use function DT\Home\redirect;
use function DT\Home\template;
use function DT\Home\view;

class TrainingController
{
    public function show( Request $request, Response $response, $key )
    {

        $training_data = $this->get_all_trainings_data();
        $data = json_encode( $training_data );
        $training_data_json_escaped = htmlspecialchars( $data );

        return template( 'training', compact(
            'data',
            'training_data',
            'training_data_json_escaped'
        ) );
    }

    protected function get_all_trainings_data()
    {
        // Get the apps array from the option
        $trainings_array = get_option( 'dt_home_trainings', [] );

        // Sort the array based on the 'sort' key
        usort($trainings_array, function ( $a, $b ) {
            return ( !empty( $a['sort'] ) && !empty( $b['sort'] ) ) ? ( $a['sort'] - $b['sort'] ) : -1;
        });

        return $trainings_array;
    }

    public function data( Request $request, Response $response, $key )
    {
        $user = wp_get_current_user();
        $data = [
            'user_login' => $user->user_login,
        ];
        $response->setContent( $data );

        return $response;
    }
}
