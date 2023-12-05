<?php

namespace DT\Launcher\Services;

use DT\Launcher\FastRoute;
use function DT\Launcher\container;
use function DT\Launcher\plugin;

class Router {
    public $route_info = null;

    /**
     * Load routes from a file
     *
     * @param $path
     * @param $options
     *
     * @return void
     */
    public function from_file( $file, $options = [] ): Router {
        $http_method          = sanitize_text_field( wp_unslash( $_SERVER['REQUEST_METHOD'] ?? 'GET' ) );
        $uri                  = sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ?? '/' ) );
        $include_query_string = $options['query_string'] ?? false;
        $directory            = $options['directory'] ?? plugin()->routes_path;
        $path                 = '/' . trim( $directory, '/' ) . '/' . $file;
        $param                = $options['param'] ?? null;
        $param_value          = null;

        if ( $param ) {
            $param_value          = sanitize_text_field( wp_unslash( $_GET[ $param ] ?? null ) );
            $include_query_string = false;
        }

        if ( ! $include_query_string ) {
            $pos = strpos( $uri, '?' );
            // Strip query string (?foo=bar) and decode URI
            if ( $pos !== false ) {
                $uri = substr( $uri, 0, $pos );
            }

            if ( $param && $param_value ) {
                $uri = $uri . '?' . http_build_query( [ $param => $param_value ] );
            }
        }

        $uri = trim( rawurldecode( $uri ), '/' );

        $dispatcher = FastRoute\simpleDispatcher( function ( FastRoute\RouteCollector $r ) use ( $path ) {
            require_once $path;
        } );

        $this->route_info = $dispatcher->dispatch( $http_method, $uri );

        return $this;
    }

    /**
     * Call the matched route
     *
     * @return void
     */
    public function make(): Router {
        if ( ! $this->is_match() ) {
            return $this;
        }


        $handler = $this->route_info[1];
        $vars    = $this->route_info[2];
        [ $class, $method ] = explode( '@', $handler, 2 );
        call_user_func_array( [ container()->make( $class ), $method ], $vars );

        return $this;
    }

    /**
     * Is the route a match?
     *
     * @return bool
     */
    public function is_match(): bool {
        if ( ! $this->route_info ) {
            return false;
        }

        return $this->route_info[0] === FastRoute\Dispatcher::FOUND;
    }
}
