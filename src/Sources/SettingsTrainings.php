<?php

namespace DT\Home\Sources;

use function DT\Home\get_plugin_option;
use function DT\Home\set_plugin_option;

class SettingsTrainings extends AppSource {

    /**
     * Retrieves the raw array of training videos.
     *
     * @return array The raw array of training videos.
     */
    public function raw( array $params = [] ): array {
        return get_plugin_option( 'trainings' );
    }

    /**
     * Save training videos.
     *
     * @param array $apps The training videos to be saved.
     * @param array $options
     * @return bool Whether the saving was successful or not.
     */
    public function save( $apps, array $options = [] ): bool {
        return set_plugin_option( 'trainings', $apps );
    }
}
