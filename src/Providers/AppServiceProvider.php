<?php

namespace DT\Home\Providers;

use DT\Home\Illuminate\Support\Arr;
use DT\Home\Services\Apps;

class AppServiceProvider extends ServiceProvider {

	public function register(): void {
		add_filter( 'dt_home_apps', [ $this, 'dt_home_apps' ] );
	}

	public function dt_home_apps( $apps ) {
		$plugins = get_plugins();
		$apps_service = $this->container->make( Apps::class );
		$bible_plugin_installed = Arr::exists( $plugins, 'bible-plugin/bible-plugin.php' );
		$bible_plugin_app = array_search(function ( $app ) {
			return $app['slug'] === 'the-bible-plugin';
		}, $apps );
		dd( $bible_plugin_app );
		if ( $bible_plugin_installed && !$bible_plugin_app ) {
			$apps[] = [
				'name' => __( 'Bible', 'dt-home' ),
				'type' => 'custom',
				'icon' => '$icon_url',
				'slug' => 'the-bible-plugin',
				'is_hidden' => 0
			];
		} elseif ( !$bible_plugin_installed && $bible_plugin_app ) {
			$apps = array_filter( $apps, function ( $app ) {
				return $app['slug'] !== 'the-bible-plugin';
			} );
		}
		return $apps;
	}

	public function boot(): void {
		// TODO: Implement boot() method.
	}
}
