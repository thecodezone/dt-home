<?php

namespace DT\Home\Services;

use DT\Home\Illuminate\Support\Arr;
use function DT\Home\container;
use function DT\Home\get_magic_url;

class Apps {
	/**
	 * Retrieve all apps from the option and sort them based on the 'sort' key.
	 *
	 * @return array The sorted array of apps.
	 */
	public function all() {
		// Get the apps array from the option
		$home_apps = get_option( 'dt_home_apps', [] );
        $home_apps = apply_filters( 'dt_home_apps', $home_apps );

        $apps = [];
        foreach ( $home_apps as $app ){
            $apps[$app['slug']] = $app;
        }

        $magic_apps = apply_filters( 'dt_magic_url_register_types', [] );
        foreach ( $magic_apps as $root_key => $root_value ){
            foreach ( $root_value as $type_key => $app ){
                if ( empty( $app['meta']['show_in_home_apps'] ) ){
                    continue;
                }
                if ( $app['post_type'] === 'user' ){
                    $app_link = get_magic_url( $app['root'], $app['type'], get_current_user_id() );
                }
                if ( $app['post_type'] === 'contacts' ){
                    $app_link = get_magic_url( $app['root'], $app['type'], \Disciple_Tools_Users::get_contact_for_user( get_current_user_id() ) );
                }
                $apps[$app['type']] = array_merge( [
                    'name' => $app['label'],
                    'type' => 'Web View',
                    'icon' => $app['meta']['icon'] ?? '/wp-content/themes/disciple-tools-theme/dt-assets/images/link.svg',
                    'url' => $app_link,
                    'slug' => $app['type'],
                    'sort' => $app['sort'] ?? 10,
                    'is_hidden' => false,
                ], $apps[$app['type']] ?? [] );
            }
        }

		// Sort the array based on the 'sort' key
		usort($apps, function ( $a, $b ) {
			return ( $a['sort'] ?? 0 ) - ( $b['sort'] ?? 0 );
		});

		return $this->format( $apps );
	}

	/**
	 * Retrieve the apps array for a specific user.
	 * If the user has a specific apps array set, it will be returned.
	 * Otherwise, the default apps array will be returned.
	 *
	 * @param int $user_id The ID of the user.
	 *
	 * @return array The apps array for the user.
	 */
	public function for_user( $user_id ) {
		$user_apps = get_user_option( 'dt_home_apps', $user_id );
		if ( ! $user_apps ) {
			$user_apps = [];
		}
		$apps = $this->all();


		foreach ( $apps as $idx => $app ) {
			$matching_user_apps = array_filter( $user_apps, function ( $user_app ) use ( $app ) {
				return ( $user_app['slug'] ?? '' ) === ( $app['slug'] ?? '' );
			} );

			if ( ! empty( $matching_user_apps ) ) {
				$apps[ $idx ] = array_merge(
					$app,
					Arr::only( Arr::first( $matching_user_apps ), [ 'sort' , 'is_hidden' ] )
				);
			}
		}

		// Sort the array based on the 'sort' key
		usort($apps, function ( $a, $b ) {
			return ( $a['sort'] ?? 0 ) - ( $b['sort'] ?? 0 );
		});

		return $apps;
	}

	private function format( $apps ) {
		$apps = array_map(function ( $app ) {
			return array_merge([
				'name' => '',
				'type' => 'webview',
				'icon' => '',
				'url' => '',
				'sort' => 0,
				'slug' => '',
				'is_hidden' => false,
			], $app);
		}, $apps);

		return $apps;
	}
}
