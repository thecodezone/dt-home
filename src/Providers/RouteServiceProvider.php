<?php

namespace DT\Launcher\Providers;

use DT\Launcher\Services\Router;
use DT\Launcher\Services\Template;
use function DT\Launcher\plugin;

class RouteServiceProvider extends ServiceProvider {

	/**
	 * Do any setup needed before the theme is ready.
	 * DT is not yet registered.
	 */
	public function register(): void {
	}

	/**
	 * Do any setup after services have been registered and the theme is ready
	 */
	public function boot(): void {
		add_action( 'rest_api_init', [ $this, 'registerRestRoutes' ], 1 );
		$this->registerWebRoutes();
	}

	/**
	 * Register web-based routes
	 *
	 * @return void
	 */
	public function registerWebRoutes(): void {
		$router = $this->container->make( Router::class );
		$router->from_file( 'web/routes.php' );

		if ( $router->is_match() ) {
			$template = $this->container->make( Template::class );
			$template->make(
				function () use ( $router ) {
					$router->make();
				}
			);
		}
	}

	/**
	 * @return void
	 */
	public function registerRestRoutes() {
		require_once plugin()->routes_path . '/rest/routes.php';
	}
}
