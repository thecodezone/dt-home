<?php

namespace DT\Launcher\Providers;

use DT\Launcher\Illuminate\Http\Request;

class PluginServiceProvider extends ServiceProvider {
	/**
	 * List of providers to register
	 *
	 * @var array
	 */
	protected $providers = [
		ViewServiceProvider::class,
		ConditionsServiceProvider::class,
		MiddlewareServiceProvider::class,
		AdminServiceProvider::class,
		//PostTypeServiceProvider::class,
		MagicLinkServiceProvider::class,
		RouterServiceProvider::class,
	];

	/**
	 * Do any setup needed before the theme is ready.
	 * DT is not yet registered.
	 */
	public function register(): void {
		$this->container->singleton( Request::class, function () {
			return Request::capture();
		} );

		foreach ( $this->providers as $provider ) {
			$provider = $this->container->make( $provider );
			$provider->register();
		}
	}

	/**
	 * Do any setup after services have been registered and the theme is ready
	 */
	public function boot(): void {
		foreach ( $this->providers as $provider ) {
			$provider = $this->container->make( $provider );
			$provider->boot();
		}
	}
}
