<?php

namespace DT\Home\Providers;

use DT\Home\CodeZone\Router;
use DT\Home\CodeZone\Router\FastRoute\Routes;
use DT\Home\FastRoute\RouteCollector;
use DT\Home\Plugin;
use function DT\Home\routes_path;


class RouterServiceProvider extends ServiceProvider {
	/**
	 * Do any setup needed before the theme is ready.
	 * DT is not yet registered.
	 */
	public function register(): void {
		Router::register( [
			'container' => $this->container,
			'route_param' => Plugin::ROUTE_QUERY_PARAM,
		] );

		add_filter( Router\namespace_string( "routes" ), [ $this, 'include_route_file' ], 1 );
	}

	/**
	 * DT is ready. Do any setup needed after the theme is ready.
	 */
	public function boot(): void {
	}

	/**
	 * Register the routes for the application.
	 *
	 * @param Routes $r
	 *
	 * @return Routes
	 */
	public function include_route_file( Routes $r ): RouteCollector {

		include routes_path( 'web.php' );

		return $r;
	}
}
