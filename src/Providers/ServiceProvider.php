<?php

namespace DT\Launcher\Providers;

use DT\Launcher\Illuminate\Container\Container;

abstract class ServiceProvider {
	protected $container;

	public function __construct( Container $container ) {
		$this->container = $container;
	}

	/**
	 * Do any setup needed before the theme is ready.
	 * DT is not yet registered.
	 */
	abstract public function register(): void;

	/**
	 * Do any setup after services have been registered and the theme is ready
	 */
	abstract public function boot(): void;
}
