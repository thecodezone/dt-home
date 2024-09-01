<?php

namespace DT\Home\Providers;

use DT\Home\Illuminate\Filesystem\Filesystem;
use DT\Home\Illuminate\Http\Request;
use DT\Home\Illuminate\Translation\FileLoader;
use DT\Home\Illuminate\Translation\Translator;
use DT\Home\Illuminate\Validation\Factory;

class PluginServiceProvider extends ServiceProvider {
	/**
	 * List of providers to register
	 *
	 * @var array
	 */
	protected $providers = [
		AppServiceProvider::class,
		ViewServiceProvider::class,
		ConditionsServiceProvider::class,
		CapabilitiesServiceProvider::class,
		MiddlewareServiceProvider::class,
		AssetServiceProvider::class,
		AdminServiceProvider::class,
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

		$this->registerValidator();
	}

	/**
	 * Register the validator
	 */
	protected function registerValidator(): void {
		$this->container->bind( FileLoader::class, function ( $container ) {
			return new FileLoader( $container->make( Filesystem::class ), 'lang' );
		} );

		$this->container->bind( Factory::class, function ( $container ) {
			$loader     = $container->make( FileLoader::class );
			$translator = new Translator( $loader, 'en' );

			return new Factory( $translator, $container );
		} );
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
