<?php

namespace DT\Home\Providers;

use DT\Home\CodeZone\Router;
use DT\Home\CodeZone\Router\Middleware\DispatchController;
use DT\Home\CodeZone\Router\Middleware\HandleErrors;
use DT\Home\CodeZone\Router\Middleware\HandleRedirects;
use DT\Home\CodeZone\Router\Middleware\Middleware;
use DT\Home\CodeZone\Router\Middleware\Render;
use DT\Home\CodeZone\Router\Middleware\Route;
use DT\Home\CodeZone\Router\Middleware\Stack;
use DT\Home\CodeZone\Router\Middleware\UserHasCap;
use DT\Home\CodeZone\Router\Middleware\SetHeaders;
use DT\Home\League\Container\ServiceProvider\AbstractServiceProvider;
use DT\Home\League\Container\ServiceProvider\BootableServiceProviderInterface;
use DT\Home\Middleware\CheckShareCookie;
use DT\Home\Middleware\LoggedIn;
use DT\Home\Middleware\LoggedOut;
use DT\Home\Middleware\MagicLink;
use DT\Home\Middleware\Nonce;
use DT\Home\Middleware\SetBypassCookie;
use DT\Home\Middleware\UnCached;
use Exception;
use function DT\Home\namespace_string;

/**
 * Request middleware to be used in the request lifecycle.
 *
 * Class MiddlewareServiceProvider
 * @package DT\Home\Providers
 */
class MiddlewareServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface {
	protected $middleware = [
		Route::class,
		DispatchController::class,
		HandleErrors::class,
		HandleRedirects::class,
		SetHeaders::class,
        SetBypassCookie::class,
        UnCached::class,
		Render::class,
	];

	/**
	 * Registers the middleware for the plugin.
	 *
	 * This method adds a filter to register middleware for the plugin.
	 * The middleware is added to the stack in the order it is defined above.
	 *
	 * @return void
	 */
	public function boot(): void {
		add_filter( namespace_string( 'middleware' ), function ( Stack $stack ) {
			$stack->push( ...$this->middleware );

			return $stack;
		} );

		add_filter( Router\namespace_string( 'middleware' ), function ( array $middleware ) {
			return array_merge( $middleware, $this->route_middleware );
		} );

		/**
		 * Parse named signature to instantiate any middleware that takes arguments.
		 * Signature format: "name:signature"
		 */
		add_filter( Router\namespace_string( 'middleware_factory' ), function ( Middleware|null $middleware, $attributes ) {
	        return null;
		}, 10, 2 );
	}

	/**
	 * Do anything we need to do after the theme loads.
	 *
	 * @return void
	 */
	public function register(): void {
	}

    public function provides( string $id ): bool
    {
        return false;
    }
}
