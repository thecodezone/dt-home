<?php

namespace DT\Launcher\Providers;
use DT\Launcher\MagicLinks\UserMagicLink;

class MagicLinkServiceProvider extends ServiceProvider {
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
		$this->container->singleton(
			UserMagicLink::class
		);
		$this->container->make( UserMagicLink::class );
	}
}
