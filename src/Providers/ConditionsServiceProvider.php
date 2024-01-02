<?php

namespace DT\Launcher\Providers;

use DT\Launcher\CodeZone\Router\Conditions\HasCap;
use DT\Launcher\Conditions\Backend;
use DT\Launcher\Conditions\Frontend;
use DT\Launcher\Conditions\Plugin;

class ConditionsServiceProvider extends ServiceProvider {
	protected $conditions = [
		'can'      => HasCap::class,
		'backend'  => Backend::class,
		'frontend' => Frontend::class,
		'plugin'   => Plugin::class
	];

	/**
	 * Registers the middleware for the plugin.
	 *
	 * This method adds a filter to register middleware for the plugin.
	 * The middleware is added to the stack in the order it is defined above.
	 *
	 * @return void
	 */
	public function register(): void {
		add_filter( 'codezone/router/conditions', function ( array $middleware ) {
			return array_merge( $middleware, $this->conditions );
		} );
	}

	public function boot(): void {
		// TODO: Implement boot() method.
	}
}
