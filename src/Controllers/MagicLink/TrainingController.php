<?php

namespace DT\Home\Controllers\MagicLink;

use DT\Home\Psr\Http\Message\ResponseInterface;
use function DT\Home\get_plugin_option;
use function DT\Home\template;

class TrainingController
{
    /**
     * Retrieves all training data and returns it as a JSON response.
     *
     * @return ResponseInterface
     */
    public function show()
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

    /**
     * Get all training data.
     *
     * This method retrieves the apps array from the 'trainings' option and sorts it based on the 'sort' key.
     *
     * @return array The sorted trainings array.
     */
    protected function get_all_trainings_data()
    {
        // Get the apps array from the option
        $trainings_array = get_plugin_option( 'trainings' );

        // Sort the array based on the 'sort' key
        usort($trainings_array, function ( $a, $b ) {
            return ( !empty( $a['sort'] ) && !empty( $b['sort'] ) ) ? ( $a['sort'] - $b['sort'] ) : -1;
        });

        return $trainings_array;
    }
}
