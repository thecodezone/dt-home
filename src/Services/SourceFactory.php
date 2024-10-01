<?php

namespace DT\Home\Services;

use function DT\Home\config;
use function DT\Home\container;

/**
 * Factory for creating instances of sources.
 */
class SourceFactory {
    /**
     * Make an instance of the given source using the container.
     *
     * @param mixed $source The source to create an instance of.
     *                      Can be either an object or a string.
     * @param array $params Additional parameters to pass to the instance.
     *
     * @return object The created instance of the source.
     *
     * @throws \InvalidArgumentException If the source type is invalid.
     */
    public static function make( $source, array $params = [] ) {
        return container()->get( self::as_classname( $source ), $params );
    }

    /**
     * Get the class name of the given source.
     *
     * @param mixed $source The source to get the class name from.
     *                      Can be either an object or a string.
     * @return string The class name of the source.
     *
     * @throws \InvalidArgumentException If the source type is invalid.
     */
    public static function as_classname( $source ): string {
        if ( is_object( $source ) ) {
            return get_class( $source );
		}

        if ( ! is_string( $source ) ) {
            return throw new \InvalidArgumentException( 'Invalid source type' );
		}

        return self::handle_to_classname( $source );
    }

    /**
     * Get the class name based on the handle.
     *
     * @param mixed $handle The handle to get the class name from.
     * @return string The class name associated with the handle.
     */
    public static function handle_to_classname( $handle ) {
        $handles = config( 'apps.source_handles', [] );
        return $handles[$handle] ?? $handle;
    }

    /**
     * Transforms a class name to a corresponding handle.
     *
     * @param string $classname The class name to transform.
     * @return string The corresponding handle.
     */
    public static function classname_to_handle( $classname ) {
        $handles = config( 'apps.source_handles', [] );
        //phpcs:ignore
        return array_search( $classname, $handles ) ?: $classname;
    }
}
