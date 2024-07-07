<?php

namespace DT\Home\Apps;

use DT\Home\Illuminate\Support\Arr;
use DT\Home\Illuminate\Support\Collection;
use function DT\Home\collect;

/**
 * Base class for custom apps.
 *
 * @package your_namespace
 */
abstract class App {
	public function __construct() {
		if ( !$this->authorized() ) {
			return;
		}
		add_filter( 'dt_home_apps', [ $this, 'dt_home_apps' ] );
		if ( method_exists( $this, 'render' ) !== false ) {
			add_action( 'dt_home_app_render', [ $this, 'dt_home_app_render' ], 10 );
		}
		if ( method_exists( $this, 'template' ) !== false ) {
			add_action( 'dt_home_app_template', [ $this, 'dt_home_app_template' ], 10, 2 );
		}
		if ( method_exists( $this, 'url' ) !== false ) {
			add_action( 'dt_home_webview_url', [ $this, 'dt_home_webview_url' ], 10, 2 );
		}
	}

	/**
	 * Retrieves the configuration settings.
	 *
	 * @return array The configuration settings.
	 */
	abstract public function config(): array;

	/**
	 * Checks if app should be available
	 *
	 * @return bool Returns true if the user is authorized, false otherwise.
	 */
	abstract public function authorized(): bool;

	/**
	 * Register the custom app if there is a slug
	 *
	 * @param array $apps The home apps array.
	 *
	 * @return array The modified home apps array.
	 */
	public function dt_home_apps( $apps ) {
		$apps = collect( $apps );
		$slug = $this->config()['slug'];
		if ( ! $slug ) {
			return $apps;
		}
		return $this->register( $apps );
	}

	/**
	 * Registers an app in the collection.
	 *
	 * @param Collection $apps The collection of apps.
	 *
	 * @return array The updated collection of apps as an array.
	 */
	protected function register( Collection $apps ) {
		$slug = $this->config()['slug'];
		$app = $apps->where( 'slug', '=', $slug )->first() ?? [];
		$apps = $apps->reject( function ( $item ) use ( $slug ) {
			return $item['slug'] === $slug;
		} );
		$app = array_merge( $this->config(), $app, Arr::only($this->config(), [
			'type',
			'slug',
			'url'
		]) );
		$apps->push( $app );
		return $apps->values()->toArray();
	}

	/**
	 * Renders the home app.
	 *
	 * @param array $app The app to render.
	 *
	 * @return array The rendered app.
	 */
	public function dt_home_app_render( $app ) {
		if ( $app['slug'] !== $this->config()['slug'] ) {
			return $app;
		}

		$this->render();
	}

	/**
	 * Returns the template for the home app.
	 *
	 * @param string $template The template to use.
	 * @param array $app The app to check for slug match.
	 *
	 * @return string The template for the home app or the original template if slug does not match.
	 */
	public function dt_home_app_template( $template, $app ) {
		if ( $app['slug'] !== $this->config()['slug'] ) {
			return $template;
		}

		return $this->template();
	}

	public function dt_home_webview_url( $url, $app ) {
		if ( $app['slug'] !== $this->config()['slug'] ) {
			return;
		}

		return $this->url();
	}
}
