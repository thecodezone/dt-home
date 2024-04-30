<?php

namespace DT\Home\Services;

class Apps {
	/**
	 * Retrieve all apps from the option and sort them based on the 'sort' key.
	 *
	 * @return array The sorted array of apps.
	 */
	public function all() {
		// Get the apps array from the option
		$apps_array = get_option( 'dt_home_apps', [] );

		// Sort the array based on the 'sort' key
		usort($apps_array, function ( $a, $b ) {
			return $a['sort'] - $b['sort'];
		});

		return apply_filters( 'dt_home_apps', $apps_array );
	}

	public function for_user( $user_id ) {
		$apps_array = get_user_option( 'dt_home_apps', $user_id );

		// Fallback to default option if user option is not set
		if ( $apps_array === false ) {
			$apps_array = get_option( 'dt_home_apps' );
		}

		return apply_filters( 'dt_home_apps', $apps_array );
	}

	/**
	 * Retrieve a specific app by its ID.
	 *
	 * @param int $id The ID of the app to retrieve.
	 *
	 * @return array|false The app with the specified ID if found, false otherwise.
	 */
	public function get( $id ) {
		$apps = $this->all();

		foreach ( $apps as $app ) {
			if ( isset( $app['id'] ) && $app['id'] == $id ) {
				return $app;
			}
		}

		return false;
	}

	public function get_by_slug( $slug ) {
		$apps = $this->all();

		foreach ( $apps as $app ) {
			if ( isset( $app['slug'] ) && $app['slug'] == $slug ) {
				return $app;
			}
		}

		return false;
	}

	/**
	 * Save data to storage
	 *
	 * This method stores the given data to the storage. It generates a unique ID for the
	 * new data by finding the highest ID in the existing data and increments it by 1.
	 * The updated data is then saved using the save() method.
	 *
	 * @param array $data The data to be stored
	 *
	 * @return mixed The result of the save operation
	 */
	public function create( array $data ) {
		// Get the existing apps array
		$apps_array = $this->all();

		// Generate a unique ID for the new app
		$next_id = 1;
		foreach ( $apps_array as $app ) {
			if ( isset( $app['id'] ) && $app['id'] >= $next_id ) {
				$next_id = $app['id'] + 1;
			}
		}

		$data['id'] = $next_id;

		return $this->save( $data );
	}

	/**
	 * Update an existing app with new data.
	 *
	 * This method updates an existing app with the given data. The app is identified by its ID.
	 *
	 * @param int $id The ID of the app to update.
	 * @param array $data The new data for the app.
	 *
	 * @return mixed The result of the save operation
	 */
	public function update( $id, $data ) {
		// Get the existing apps array
		$apps_array = $this->all();

		// Find the app with the specified ID
		foreach ( $apps_array as $key => $app ) {
			if ( $app['id'] == $id ) {
				foreach ( $data as $field => $value ) {
					$apps_array[ $key ][ $field ] = $value;
				}
			}
		}

		return $this->save( $apps_array );
	}


	/**
	 * Save the given data into the 'dt_home_apps' option.
	 *
	 * @param array $data The data to be saved.
	 *
	 * @return bool True on success, false on failure.
	 */
	public function save( $data ) {
		return update_option( 'dt_home_apps', $data );
	}

	/**
	 * Update the sorting position of an app by moving it up.
	 *
	 * @param int $id The ID of the app.
	 *
	 * @return bool Returns true if the app's sort position was successfully updated, false otherwise.
	 */
	public function up( $id ) {
		// Find the index of the app and its current sort value
		$current_index = null;
		$current_sort = null;
		$apps_array = $this->all();
		foreach ( $apps_array as $key => $app ) {
			if ( $app['id'] == $id ) {
				$current_index = $key;
				$current_sort = $app['sort'];
				break;
			}
		}

		// Only proceed if the app was found and it's not already at the top
		if ( $current_index !== null && $current_sort > 1 ) {
			// Adjust the sort values
			foreach ( $apps_array as $key => &$app ) {
				if ( $app['sort'] == $current_sort - 1 ) {
					// Increment the sort value of the app that's currently one position above
					$app['sort']++;
				}
			}
			// Decrement the sort value of the current app
			$apps_array[$current_index]['sort']--;

			// Re-sort the array
			usort($apps_array, function ( $a, $b ) {
				return $a['sort'] - $b['sort'];
			});

			return $this->save( $apps_array );
		}
	}

	/**
	 * Update the sorting position of an app by moving it down.
	 *
	 * @param int $id The ID of the app.
	 *
	 * @return void
	 */
	public function down( $id ) {
		// Retrieve the existing array of apps
		$apps_array = $this->all();

		// Find the index of the app and its current sort value
		$current_index = null;
		$current_sort = null;
		foreach ( $apps_array as $key => $app ) {
			if ( $app['id'] == $id ) {
				$current_index = $key;
				$current_sort = $app['sort'];
				break;
			}
		}

		// Determine the maximum sort value
		$max_sort = count( $apps_array );

		// Only proceed if the app was found and it's not already at the bottom
		if ( $current_index !== null && $current_sort < $max_sort ) {
			// Adjust the sort values
			foreach ( $apps_array as $key => &$app ) {
				if ( $app['sort'] == $current_sort + 1 ) {
					// Decrement the sort value of the app that's currently one position below
					$app['sort']--;
				}
			}
			// Increment the sort value of the current app
			$apps_array[$current_index]['sort']++;

			// Re-sort the array
			usort($apps_array, function ( $a, $b ) {
				return $a['sort'] - $b['sort'];
			});

			// Save the updated array back to the option
			$this->save( $apps_array );
		}
	}
}
