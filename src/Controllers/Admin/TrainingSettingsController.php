<?php

namespace DT\Home\Controllers\Admin;

use DT\Home\Illuminate\Http\RedirectResponse;
use DT\Home\Illuminate\Http\Request;
use DT\Home\Illuminate\Http\Response;
use function DT\Home\view;

class TrainingSettingsController {

	/**
	 * Show the general settings app tab
	 */
	public function show_training_tab( Request $request, Response $response ) {

		$tab        = "training";
		$link       = 'admin.php?page=dt_home&tab=';
		$page_title = "Home Settings";

		$data = $this->get_all_trainings_data();

		return view( "settings/training", compact( 'tab', 'link', 'page_title', 'data' ) );
	}

	protected function get_all_trainings_data() {
		// Get the apps array from the option
		$trainings_array = get_option( 'dt_home_trainings', [] );

		// Sort the array based on the 'sort' key
		usort( $trainings_array, function ( $a, $b ) {
			return $a['sort'] - $b['sort'];
		} );

		return $trainings_array;
	}


	public function create_training( Request $request, Response $response ) {
		$tab        = "training";
		$link       = 'admin.php?page=dt_home&tab=';
		$page_title = "Home Settings";

		return view( "settings/training/create", compact( 'tab', 'link', 'page_title' ) );
	}

	public function store( Request $request, Response $response ) {
		// Retrieve form data
		$name        = $request->input( 'name' );
		$embed_video = $request->input( 'embed_video' );
		$anchor      = $request->input( 'anchor' );
		$sort        = $request->input( 'sort' );

		// Prepare the data to be stored
		$training_data = [
			'name'        => $name,
			'embed_video' => $embed_video,
			'anchor'      => $anchor,
			'sort'        => $sort,
		];

		// Get the existing apps array
		$trainings_array = get_option( 'dt_home_trainings', [] );

		// Generate a unique ID for the new app
		$next_id = 1;
		foreach ( $trainings_array as $training ) {
			if ( isset( $training['id'] ) && $training['id'] >= $next_id ) {
				$next_id = $training['id'] + 1;
			}
		}

		$training_data['id'] = $next_id; // Add the ID to the new app data

		// Append new app data to the array
		$trainings_array[] = $training_data;

		// Save the updated array back to the option
		update_option( 'dt_home_trainings', $trainings_array );

		$response = new RedirectResponse( 'admin.php?page=dt_home&tab=training&updated=true', 302 );

		return $response;
	}

	public function edit_training( $id ) {
		$edit_id = isset( $id ) ? intval( $id ) : 0;

		if ( $edit_id ) {
			// Retrieve the existing data based on $edit_id
			$existing_data = $this->get_data_by_id( $edit_id );

			$tab        = "training";
			$link       = 'admin.php?page=dt_home&tab=';
			$page_title = "Home Settings";

			if ( $existing_data ) {
				// Load the edit form view and pass the existing data
				return view( "settings/training/edit", compact( 'existing_data', 'link', 'tab', 'page_title' ) );
			}
		}
	}


	public function get_data_by_id( $id ) {
		$trainings_array = get_option( 'dt_home_trainings', [] );
		foreach ( $trainings_array as $training ) {
			if ( isset( $training['id'] ) && $training['id'] == $id ) {
				return $training;
			}
		}

		return null; // Return null if no app is found with the given ID
	}

	public function delete( $id ) {
		// Retrieve the existing array of trainings
		$trainings_array = get_option( 'dt_home_trainings', [] );

		// Find the app with the specified ID and remove it from the array
		foreach ( $trainings_array as $key => $training ) {
			if ( isset( $training['id'] ) && $training['id'] == $id ) {
				unset( $trainings_array[ $key ] ); // Remove the app from the array
				break; // Exit the loop once the app is found and removed
			}
		}

		// Save the updated array back to the option
		update_option( 'dt_home_trainings', $trainings_array );

		// Redirect to the page with a success message
		$response = new RedirectResponse( 'admin.php?page=dt_home&tab=training&updated=true', 302 );

		return $response;
	}

	public function up( $id ) {
		// Retrieve the existing array of apps
		$trainings_array = get_option( 'dt_home_trainings', [] );

		// Find the index of the app and its current sort value
		$current_index = null;
		$current_sort  = null;
		foreach ( $trainings_array as $key => $training ) {
			if ( $training['id'] == $id ) {
				$current_index = $key;
				$current_sort  = $training['sort'];
				break;
			}
		}

		// Only proceed if the app was found and it's not already at the top
		if ( $current_index !== null && $current_sort > 1 ) {
			// Adjust the sort values
			foreach ( $trainings_array as $key => &$training ) {
				if ( $training['sort'] == $current_sort - 1 ) {
					// Increment the sort value of the app that's currently one position above
					$training['sort']++;
				}
			}
			// Decrement the sort value of the current app
			$trainings_array[ $current_index ]['sort']--;

			// Re-sort the array
			usort( $trainings_array, function ( $a, $b ) {
				return $a['sort'] - $b['sort'];
			} );

			// Save the updated array back to the option
			update_option( 'dt_home_trainings', $trainings_array );
		}

		// Redirect to the page with a success message
		$response = new RedirectResponse( 'admin.php?page=dt_home&tab=training&updated=true', 302 );

		return $response;
	}

	public function down( $id ) {
		// Retrieve the existing array of apps
		$trainings_array = get_option( 'dt_home_trainings', [] );

		// Find the index of the app and its current sort value
		$current_index = null;
		$current_sort  = null;
		foreach ( $trainings_array as $key => $app ) {
			if ( $app['id'] == $id ) {
				$current_index = $key;
				$current_sort  = $app['sort'];
				break;
			}
		}

		// Determine the maximum sort value
		$max_sort = count( $trainings_array );

		// Only proceed if the app was found and it's not already at the bottom
		if ( $current_index !== null && $current_sort < $max_sort ) {
			// Adjust the sort values
			foreach ( $trainings_array as $key => &$app ) {
				if ( $app['sort'] == $current_sort + 1 ) {
					// Decrement the sort value of the app that's currently one position below
					$app['sort']--;
				}
			}
			// Increment the sort value of the current app
			$trainings_array[ $current_index ]['sort']++;

			// Re-sort the array
			usort( $trainings_array, function ( $a, $b ) {
				return $a['sort'] - $b['sort'];
			} );

			// Save the updated array back to the option
			update_option( 'dt_home_trainings', $trainings_array );
		}

		// Redirect to the page with a success message
		$response = new RedirectResponse( 'admin.php?page=dt_home&tab=training&updated=true', 302 );

		return $response;
	}


	public function update( Request $request, Response $response ) {
		if ( isset( $_POST['submit'] ) ) {
			$name        = $request->input( 'name' );
			$embed_video = $request->input( 'embed_video' );
			$anchor      = $request->input( 'anchor' );
			$sort        = $request->input( 'sort' );

			// Get the ID of the item being edited
			$edit_id = $request->input( 'edit_id' );

			// Retrieve the existing array of apps
			$trainings_array = get_option( 'dt_home_trainings', [] );

			// Find and update the app in the array
			foreach ( $trainings_array as $key => $training ) {
				if ( $training['id'] == $edit_id ) {
					$trainings_array[ $key ] = [
						'id'          => $edit_id,
						'name'        => $name,
						'embed_video' => $embed_video,
						'anchor'      => $anchor,
						'sort'        => $sort,
					];
					break; // Stop the loop once the app is found and updated
				}
			}

			// Save the updated array back to the option
			update_option( 'dt_home_trainings', $trainings_array );

			// Redirect to the page with a success message
			$response = new RedirectResponse( 'admin.php?page=dt_home&tab=training&updated=true', 302 );

			return $response;
		}
	}
}
