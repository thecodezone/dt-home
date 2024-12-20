<?php

namespace DT\Home\Sources;

use DT\Home\Services\Analytics;
use function DT\Home\container;
use function DT\Home\get_plugin_option;
use function DT\Home\set_plugin_option;

class Trainings extends Source
{

    public function find_key()
    {
        return 'id';
    }

    /**
     * Retrieves the raw array of training videos.
     *
     * @return array The raw array of training videos.
     */
    public function raw( array $params = [] ): array
    {
        $raw_trainings = array_values( get_plugin_option( 'trainings', [] ) );

        if ( !is_array( $raw_trainings ) ) {
            $raw_trainings = [];
        }

        return $raw_trainings;
    }

    /**
     * Save training videos.
     *
     * @param array $apps The training videos to be saved.
     * @param array $options
     * @return bool Whether the saving was successful or not.
     */
    public function save( $apps, array $options = [] ): bool
    {
        return set_plugin_option( 'trainings', $apps );
    }

    /**
     * Retrieves all coded training videos.
     *
     * @return array All training videos data.
     */
    public function format_item( array $item ): array
    {
        $overrides = [];

        return array_merge([
            'id' => '',
            'name' => '',
            'embed_video' => '',
            'anchor' => '',
            'sort' => '',
        ], $item, $overrides);
    }

    /**
     * If enabled, capture a snapshot of core analytical metric counts;
     * with the ability to filter metrics to be exported.
     *
     * @param string $scope Metric scope (library.name) to be used.
     * @param array|null $trainings Trainings array to process; otherwise default to raw videos.
     * @param array $metrics Metrics to be captured.
     */
    public function capture_analytics_metric_counts( string $scope = __CLASS__, array $trainings = null, array $metrics = [
        'total-active-training-videos-count'
    ] ): void {

        $analytics = container()->get( Analytics::class );

        if ( !$analytics->is_enabled() ) {
            return;
        }

        $trainings = $trainings ?? $this->raw();

        // Process identified metrics.
        foreach ( $metrics as $metric ) {
            $properties = null;
            switch ( $metric ) {
                case 'total-active-training-videos-count':
                    $total_active_training_videos_count = count( $trainings );

                    $properties = [
                        'lib_name' => $scope,
                        'value' => $total_active_training_videos_count,
                        'unit' => 'active-training-videos',
                        'description' => 'Total Active Training Videos Count'
                    ];
                    break;
            }

            // Generate metric exports on properties detection.
            if ( !empty( $properties ) ) {
                $analytics->metric( $metric, $properties );
            }
        }
    }
}
