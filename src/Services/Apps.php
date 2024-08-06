<?php

namespace DT\Home\Services;

use DT\Home\Illuminate\Support\Arr;
use function DT\Home\container;

class Apps {
	/**
	 * Retrieve all apps from the option and sort them based on the 'sort' key.
	 *
	 * @return array The sorted array of apps.
	 */
	public function all() {
		// Get the apps array from the option
		$apps = get_option( 'dt_home_apps', [] );
		$apps = apply_filters( 'dt_home_apps', $apps );

        $magic_apps = apply_filters( 'dt_magic_url_register_types', [] );
        $magic_links = apply_filters( 'dt_settings_apps_list', [] );
        foreach ( $magic_links as $app_key => $app_value ){
            $display = $app_value['settings_display'] ?? true;
            if ( $display === true ){
                $app_user_key = get_user_option( $app_key );
                $app_url_base = trailingslashit( trailingslashit( site_url() ) . $app_value['url_base'] );
                $app_link = false;
                if ( $app_user_key ){
                    $app_link = $app_url_base . $app_user_key;
                }
                $apps[] = [
                    'name' => $app_value['label'],
                    'type' => 'Web View',
                    'icon' => $app_value['icon'] ?? "/wp-content/themes/disciple-tools-theme/dt-assets/images/link.svg",
                    'url' => $app_link,
                    'slug' => $app_value['key'],
                    'sort' => $app_value['sort'] ?? 0,
                    'is_hidden' => false,
                ];
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
