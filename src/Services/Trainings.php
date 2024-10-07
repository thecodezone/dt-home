<?php

namespace DT\Home\Services;

use DT\Home\Sources\SettingsTrainings;
use function DT\Home\container;

class Trainings {

    public function __construct() {
    }

    /**
     * Move identified training video in the specified direction.
     *
     * @param string $id
     * @param string $direction
     * @return bool
     */
    public function move( string $id, string $direction ): bool {
        if ( !empty( $id ) ) {
            $key = 'sort';
            $settings_trainings = container()->get( SettingsTrainings::class );

            // Fetch all videos in ascending order, with reset sort counts.
            $videos = $settings_trainings->uber_sort( $settings_trainings->raw(), $key, true, true );

            // Adjust sort count for specified $video.
            $videos = array_map( function ( $video ) use ( $id, $direction, $key ) {
                if ( intval( $id ) == intval( $video['id'] ) ) {

                    /**
                     * Increment or Decrement accordingly by a couple hops, to
                     * ensure new sort position falls on the lower or upper
                     * side of adjacent video; based on specified direction.
                     */

                    switch ( $direction ){
                        case 'up':
                            $video[ $key ] -= 2;
                            break;
                        case 'down':
                            $video[ $key ] += 2;
                            break;
                    }
                }

                return $video;
            }, $videos );

            // Refresh counts following adjustments.
            $videos = $settings_trainings->uber_sort( $videos, $key, true, true );

            // Save updated training videos list.
            return $settings_trainings->save( $videos );
        }

        return false;
    }
}
