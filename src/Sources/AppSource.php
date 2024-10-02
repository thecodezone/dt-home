<?php

namespace DT\Home\Sources;

use DT\Home\Services\Aggregator;
use DT\Home\Services\SourceFactory;
use function DT\Home\config;

/**
 * Base class for application sources.
 *
 * @param array $apps Array of applications.
 * @return array Deleted applications.
 */
abstract class AppSource
{
    /**
     * Returns all raw data.
     *
     * @return array An array containing all the raw data.
     */
    abstract public function raw( array $params = [] ): array;

    /**
     * Save the applications.
     */
    abstract public function save( $apps, array $options = [] ): bool;

    /**
     * Handle the application.
     *
     * @return string The name of the application.
     */
    public static function handle(): string
    {
        $handles = config( 'apps.sources', [] );
        //phpcs:ignore
        return array_search( static::class, $handles ) ?: self::class;
    }

    /**
     * Provides a place to control the way data is merged for the app source.
     *
     * @param array $existing The existing array.
     * @param array $app The app array to merge.
     * @return array The merged array.
     */
    public function merge( $existing, $app ) {
        $overrides = [];
        return array_merge( $existing, $app, $overrides );
    }

    /**
     * Returns the applications merged with its dependencies.
     *
     * @param array $params An array of parameters for filtering the items.
     * @return array An array containing all the aggregated items.
     */
    public function merged( $params = [] ) {
        $aggregator = new Aggregator( [ $this ] );
        return $aggregator->all( $params );
    }

    /**
     * Returns formatted applications.
     *
     * @param array $params The parameters for filtering applications.
     *
     * @return array An array containing the formatted applications.
     */
    public function formatted( array $params = [] ): array
    {
        return $this->format(
            $this->raw( $params )
        );
    }

    /**
     * Returns all applications.
     *
     * @return array An array containing all the items.
     */
    public function all( array $params = [] ): array {
        $filter = $params['filter'] ?? true;
        if ( $filter === true ) {
            return $this->filter(
                $this->formatted( $params )
            );
        }
        return $this->formatted( $params );
    }

    /**
     * Filters the array of applications.
     *
     * @param array $apps The array of applications to be filtered.
     *
     * @return array The filtered array of applications.
     */
    public function filter( array $apps = [] ): array {
        return array_filter( $apps, function ( $app ) {
            return $this->is_allowed( $app );
        } );
    }

    /**
     * Checks if the application is allowed.
     *
     * @param array $app The application data.
     *
     * @return bool True if the application is allowed, false otherwise.
     */
    public function is_allowed( array $app ): bool {
        return true;
    }

    /**
     * Filters and retrieves disallowed applications.
     *
     * @param array $apps Array of applications.
     * @return array Disallowed applications.
     */
    public function disallowed( array $apps ): array {
        return array_filter( $apps, function ( $app ) {
            return !$this->is_allowed( $app );
        } );
    }

    /**
     * Formats the given applications.
     *
     * @param array $apps The applications to be formatted.
     *
     * @return array The formatted applications.
     */
    protected function format( $apps ): array {
        $apps = array_map(function ( $app ) {
            return apply_filters( 'dt_home_format_app', $this->format_app( $app ) );
        }, $apps);

        $this->sort( $apps );

        return $apps;
    }


    /**
     * Formats the application data.
     *
     * @param array $app The application data to format.
     *
     * @return array The formatted application data.
     */
    protected function format_app( array $app ): array {
        return array_merge([
            'name' => '',
            'type' => 'Web View',
            'creation_type' => 'custom',
            'source' => static::handle(),
            'icon' => '',
            'url' => '',
            'sort' => 10,
            'slug' => '',
            'is_hidden' => false,
            'is_deleted' => false,
        ], $app);
    }

    /**
     * Sorts the applications array by the 'sort' field in ascending order.
     *
     * @param array $apps The applications array to sort.
     *
     * @return array The applications array sorted by the 'sort' field in ascending order.
     */
    protected function sort( &$apps ): array {
        usort($apps, function ( $a, $b ) {
            return ( (int) $a['sort'] ?? 0 ) - ( (int) $b['sort'] ?? 0 );
        });

        return $apps;
    }

    /**
     * Sets the "is_hidden" property to true for a given application.
     *
     * @param string $slug The slug of the application.
     * @param array $params Optional parameters to apply when retrieving applications.
     * @return array The updated application data.
     */
    public function hide( $slug, $params = [] ) {
        $this->set( $slug, 'is_hidden', true );
        return $this->find( $slug, $params );
    }

    /**
     * Sets the "is_hidden" property to false for a given application.
     *
     * @param string $slug The slug of the application.
     * @param array $params Optional parameters to apply when retrieving applications.
     * @return array The updated application data.
     */
    public function unhide( $slug, $params = [] ) {
        $this->set( $slug, 'is_hidden', false );
        return $this->find( $slug, $params = [] );
    }

    /**
     * Find an element in the array by its slug
     *
     * @param string $slug The slug of the element to find
     * @param array $params Optional parameters to apply when retrieving applications
     * @return array The filtered array containing the element(s) with the provided slug
     */
    public function find( $slug, $params = [] ) {
        return $this->first( array_filter( $this->all( $params ), function ( $app ) use ( $slug ) {
            return $app['slug'] === $slug;
        }) );
    }

    /**
     * Find the index of an element in the array by its slug
     *
     * @param string $slug The slug of the element to find
     * @return int|false The index of the element with the provided slug, or false if not found
     */
    public function find_index( $slug, $params = [] )
    {
        $apps = $this->all( $params );
        return array_search( $slug, array_column( $apps, 'slug' ) );
    }

    /**
     * Set a value for a specific key in an element
     *
     * @param string $slug The slug of the element to modify
     * @param string $key The key of the value to set
     * @param mixed $value The new value to set
     * @param array $params Optional parameters to apply when retrieving applications
     * @param array $save_params Optional parameters to apply when saving the updated array
     * @return bool|array False if the element with the provided slug does not exist, otherwise returns the updated array after saving
     */
    public function set( $slug, $key, $value, $params = [], $save_params = [] ) {
        $apps = $this->merged( $params );
        $index = array_search( $slug, array_column( $apps, 'slug' ) );
        if ( $index === false ) {
            return false;
        }
        $apps[ $index ][ $key ] = $value;
        $this->save( $apps, $save_params );
        return $this->value( $slug, $key );
    }

    /**
     * Toggle the value of a specific key in the element with the provided slug
     *
     * @param string $slug The slug of the element
     * @param string $key The key to toggle the value of
     * @param array $params Optional parameters to apply when retrieving applications
     * @return mixed The new value of the toggled key
     */
    public function toggle( $slug, $key, array $params = [], $save_params = [] ) {
        $value = $this->value( $slug, $key, $params );
        $this->set( $slug, $key, !$value, $params, $save_params );
        return $this->value( $slug, $key, $params );
    }

    /**
     * Get the value for a specific key of an element by its slug
     *
     * @param string $slug The slug of the element to find
     * @param string $key The key of the value to retrieve
     * @return mixed|null The value associated with the given key of the element with the provided slug,
     *                    or null if no element is found with the given slug
     */
    public function value( $slug, $key, $params = [] ) {
        $apps = $this->raw( $params );
        $index = array_search( $slug, array_column( $apps, 'slug' ) );
        if ( $index === false ) {
            return null;
        }
        return $apps[ $index ][ $key ];
    }

    /**
     * Checks if the application is visible.
     *
     * @param array $app The application data.
     *
     * @return bool True if the application is visible, false otherwise.
     */
    public function is_visible( array $app ): bool {
        return ! $app['is_hidden'];
    }

    /**
     * Filters and retrieves hidden applications.
     *
     * @param array $apps Array of applications.
     * @return array Hidden applications.
     */
    public function hidden( array $apps ): array {
        return array_filter( $apps, function ( $app ) {
            return !$this->is_visible( $app );
        } );
    }

    /**
     * Returns the first element of the given array or null if the array is empty.
     *
     * @param array $apps The input array.
     * @return mixed|null The first element of the array or null if the array is empty.
     */
    public function first( $apps ) {
        return !empty( $apps ) ? $apps[array_key_first( $apps )] : null;
    }


    /**
     * Retrieves all deleted applications.
     *
     * @param array $params Optional parameters to apply when retrieving applications.
     * @return array All deleted applications data.
     */
    public function deleted( array $params = [] ): array {
        $params = array_merge( $params, [ 'filter' => false ] );
        $apps = $this->all( $params );
        $filtered = array_filter( $apps, function ( $app ) {
            return $app['is_deleted'] === true;
        } );
        return $filtered;
    }

    /**
     * Retrieves all undeleted applications.
     *
     * @param array $params Additional parameters for filtering.
     * @return array Undeleted applications data.
     */
    public function undeleted( array $params = [] ): array {
        $apps = $this->all( $params );
        $filtered = array_filter( $apps, function ( $app ) {
            return $app['is_deleted'] !== true;
        } );
        return $filtered;
    }

    /**
     * Updates an application by slug.
     *
     * @param string $slug The slug of the application.
     * @param array $app The updated application data.
     * @param array $params Optional parameters to apply when retrieving applications.
     * @param array $save_params Optional parameters to apply when saving the updated applications.
     * @return bool|array Returns false if the application is not found, otherwise returns the updated application data.
     */
    public function update( $slug, $app, array $params = [], $save_params = [] ) {
        $apps = $this->merged( $params );
        $index = array_search( $slug, array_column( $apps, 'slug' ) );
        if ( $index === false ) {
            return false;
        }
        $apps[ $index ] = $app;
        return $this->save( $apps, $save_params );
    }

    /**
     * Soft Deletes an item.
     *
     * @param string $slug The slug of the item to be deleted.
     * @param array $params Optional. Additional parameters for the deletion. Default is an empty array.
     * @param array $save_params Optional. Additional parameters for saving the changes. Default is an empty array.
     *
     * @return bool Returns true if the item was successfully deleted.
     */
    public function delete( $slug, array $params = [], $save_params = [] ) {
        return $this->set( $slug, 'is_deleted', true, $params, $save_params );
    }

    /**
     * Removes an app with a specific slug.
     *
     * @param string $slug The slug of the app to be removed.
     * @return bool|mixed Returns false if the app with the given slug is not found,
     *                   otherwise returns result of save() method.
     */
    public function destroy( $slug, array $params = [], $save_params = [] ) {
        $apps = $this->merged( $params );
        $index = array_search( $slug, array_column( $apps, 'slug' ) );
        if ( $index === false ) {
            return false;
        }
        unset( $apps[ $index ] );
        return $this->save( $apps, $save_params );
    }
}
