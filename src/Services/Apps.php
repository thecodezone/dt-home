<?php

namespace DT\Home\Services;

use DT\Home\Sources\SettingsApps;
use DT\Home\Sources\UserApps;

/**
 * Queries apps from multiple sources.
 */
class Apps {
    /**
     * Get all apps from the specified app source classname.
     *
     * @param string $app_source The class name of the app source.
     * @param array $params Optional parameters for filtering the apps.
     */
    public function from( $app_source, array $params = [] ) {
        $aggregator = new Aggregator( [
            $app_source
        ]);
        return $aggregator->all( $params );
    }

    /**
     * Alias for from method.
     *
     * @param string $app_source
     * @param array $params
     * @return array
     */
    public function source( $app_source, array $params = [] ) {
        return $this->from( $app_source, $params );
    }

    /**
     * Retrieve the apps array for a specific user.
     *
     * If the user has a specific apps array set, it will be returned.
     *
     * @param int $user_id The ID of the user.
     * @param array $params Optional parameters for filtering the apps.
     */
    public function for( int $user_id = 0, array $params = [] ) {
        if ( $user_id === 0 ) {
            $user_id = get_current_user_id();
        }
        $params['user_id'] = $user_id;
        return $this->from( UserApps::class, $params );
    }

    /**
     * Find an app for a specific user by the app's slug.
     *
     * @param string $slug The slug of the app.
     * @param int $user_id The ID of the user.
     * @param array $params Optional parameters for filtering the apps.
     * @return array|null The app with matching slug for the user, or null if not found.
     */
    public function find_for( string $slug, int $user_id, array $params = [] )
    {
        $apps = $this->for( $user_id, $params );
        $filtered_apps = $this->with_slug( $apps, $slug );
        return $this->first( $filtered_apps );
    }

    /**
     * Find an app by slug.
     *
     * @param string $slug The slug of the app.
     * @return array|null The app with matching slug, or null if not found.
     */
    public function find( string $slug, array $params = [] ) {
        $source = $params['source'] ?? SettingsApps::class;
        $apps = $this->from( $source, $params );

        return $this->first_with_slug( $apps, $slug );
    }

    /**
     * Returns the first element of the given array or null if the array is empty.
     *
     * @param array $apps The input array.
     * @return mixed|null The first element of the array or null if the array is empty.
     */
    private function first( array $apps ) {
        return !empty( $apps ) ? $apps[0] : null;
    }

    /**
     * Filter the apps array by slug and return an array of matching items.
     * @param array $apps
     * @param string $slug
     * @return array
     */
    private function with_slug( array $apps, string $slug ): array
    {
        return $this->filter( $apps, 'slug', $slug );
    }

    /**
     * Filter the apps array to find the items with matching key and value.
     *
     * @param array $apps The array of apps.
     * @param string $key The key to check for a match.
     * @param mixed $value The value to check for a match.
     *
     * @return array The filtered array of apps with matching key and value.
     */
    private function filter( array $apps, string $key, $value ): array
    {
        // Filter the $apps array to find the item with matching key and value.
        $filtered_apps = array_filter($apps, function ( $app ) use ( $key, $value ) {
            return ( $app[$key] ?? '' ) === $value;
        });

        // array_filter preserves array keys, so use array_values to reindex it
        return array_values( $filtered_apps );
    }


    /**
     * Get the first app from the apps array with the given slug.
     */
    private function first_with_slug( $apps, $slug )
    {
        $filtered_apps = $this->with_slug( $apps, $slug );
        return $this->first( $filtered_apps );
    }
}
