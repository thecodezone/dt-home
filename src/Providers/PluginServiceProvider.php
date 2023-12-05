<?php

namespace DT\Launcher\Providers;

class PluginServiceProvider extends ServiceProvider {
	protected $providers = [
		RouteServiceProvider::class,
		PostTypeServiceProvider::class,
		AdminServiceProvider::class,
		MagicLinkServiceProvider::class
	];

	/**
	 * Do any setup needed before the theme is ready.
	 * DT is not yet registered.
	 */
	public function register(): void {
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
