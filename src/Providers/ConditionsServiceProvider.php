<?php

namespace DT\Home\Providers;

use DT\Home\CodeZone\Router;
use DT\Home\CodeZone\Router\Conditions\HasCap;
use DT\Home\Conditions\Backend;
use DT\Home\Conditions\Frontend;
use DT\Home\Conditions\Plugin;

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
		add_filter( Router\namespace_string( 'conditions' ), function ( array $middleware ) {
			return array_merge( $middleware, $this->conditions );
		} );
	}

	public function boot(): void {
		// TODO: Implement boot() method.
	}
}
