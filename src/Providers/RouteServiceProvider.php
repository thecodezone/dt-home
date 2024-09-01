<?php

namespace DT\Home\Providers;

use DT\Home\CodeZone\WPSupport\Router\RouteInterface;
use DT\Home\CodeZone\WPSupport\Router\RouteServiceProvider as RouteProvider;
use DT\Home\League\Container\ServiceProvider\BootableServiceProviderInterface;
use DT\Home\League\Container\Exception\NotFoundException;
use function DT\Home\config;
use function DT\Home\namespace_string;
use function DT\Home\routes_path;

/**
 * Class RouteServiceProvider
 *
 * This class is responsible for providing routes and middleware for the application.
 *
 * @see https://route.thephpleague.com/4.x/usage/
 * @see https://php-fig.org/psr/psr-7/
 * @package Your\Namespace
 */
class RouteServiceProvider extends RouteProvider implements BootableServiceProviderInterface {

    /**
     * Represents the configuration settings for the application.
     *
     * @var array $config An associative array containing the configuration settings
     */
    protected $config;

    /**
     * Retrieves the files configuration from the config object.
     *
     * @return array The array containing file configuration.
     */
    protected function files(): array
    {
        return config()->get( 'routes.files' );
    }

    /**
     * Retrieves the middleware configuration for the routes.
     *
     * @return array The array containing the middleware configuration.
     */
    protected function middleware(): array
    {
        return config()->get( 'routes.middleware' );
    }

    /**
     * Retrieves the renderer for the response.
     *
     * This method applies the 'response_renderer' filter to the namespace string and returns the result.
     * If no renderer is found, it returns false.
     *
     * @return mixed The renderer for the response. Returns false if no renderer is found.
     */
    public function get_renderer() {
        return apply_filters( namespace_string( 'response_renderer' ), false );
    }

    /**
     * Routes a file with the given file configuration.
     *
     * @param RouteInterface $route The route to be used for routing the file.
     * @param array $file The array containing file configuration.
     * @return void
     */
    protected function route_file( RouteInterface $route, $file ) {

        $route->middleware( $this->middleware() )
            ->file( routes_path( $file['file'] ) )
            ->rewrite( $file['query'] );

        if ( WP_DEBUG ) {
            $route->dispatch();
        } else {
            try {
                $route->dispatch();
            } catch ( NotFoundException $e ) {
                return;
            }
        }

        $renderer = $this->get_renderer();

        if ( $renderer ) {
            $route->render_with( $renderer );
        }

        $route->resolve();
    }
}
