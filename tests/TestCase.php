<?php

namespace Tests;

use DT\Home\CodeZone\Router\Middleware\HandleErrors;
use DT\Home\CodeZone\Router\Middleware\HandleRedirects;
use DT\Home\CodeZone\Router\Middleware\Render;
use DT\Home\CodeZone\Router\Middleware\Stack;
use DT\Home\Illuminate\Http\Request;
use WP_UnitTestCase;
use Faker;
use function DT\Home\namespace_string;
use function DT\Home\container;

abstract class TestCase extends WP_UnitTestCase {
	/**
	 * The Faker instance.
	 *
	 * @var Faker\Generator
	 */
	protected Faker\Generator $faker;

	public function __construct( ?string $name = null, array $data = [], $data_nme = '' ) {
		$this->faker = \Faker\Factory::create();
		parent::__construct( $name, $data, $data_nme );
	}

	public function setUp(): void {
		global $wpdb;
		$wpdb->query( 'START TRANSACTION' );
		parent::setUp();
	}

	public function tearDown(): void {
		global $wpdb;
		$wpdb->query( 'ROLLBACK' );
		parent::tearDown();
	}

	/**
	 * Sends a GET request to the specified URI with optional parameters and headers.
	 *
	 * @param string $uri The URI to send the GET request to.
	 * @param mixed $parameters The optional parameters to include in the GET request.
	 * @param array $headers The optional headers to include in the GET request.
	 *
	 * @return mixed The response returned from the GET request.
	 */
	public function get( $uri, $parameters = [], array $headers = [] ) {
		return $this->request( 'GET', $uri, $parameters, $headers );
	}

	/**
	 * Makes a request to a given URI using the specified HTTP method.
	 *
	 * @param string $method The HTTP method to use for the request (e.g., GET, POST).
	 * @param string $uri The URI to send the request to.
	 * @param array $parameters An associative array of request parameters.
	 * @param array $headers An associative array of request headers.
	 * @param array $cookies An associative array of request cookies.
	 * @param array $files An associative array of request files.
	 * @param array $server An associative array of request server variables.
	 * @param mixed $content The request content.
	 *
	 * @return mixed The response of the request.
	 */
	public function request( $method, $uri, array $parameters = [], $headers = [], array $cookies = [], array $files = [], array $server = [], $content = null ) {
		$initial_request = container()->make( Request::class );
		$request         = Request::create( $uri, $method, $parameters, $cookies, $files, $server, $content );
		foreach ( $headers as $key => $value ) {
			$request->headers->set( $key, $value );
		}
		$blacklisted_middleware = [
			HandleErrors::class,
			HandleRedirects::class,
			Render::class
		];

		container()->bind( Request::class, function () use ( $request ) {
			return $request;
		} );

		add_filter( namespace_string( 'middleware' ), function ( $stack ) use ( $blacklisted_middleware ) {
			return $stack->filter( function ( $middleware ) use ( $blacklisted_middleware ) {
				return ! in_array( $middleware, $blacklisted_middleware );
			} );
		} );
		$stack    = apply_filters( namespace_string( 'middleware' ), container()->make( Stack::class ) );
		$response = $stack->run();

		container()->bind( Request::class, function () use ( $initial_request ) {
			return $initial_request;
		} );

		return $response;
	}

	/**
	 * Send a POST request to the specified URI with the given data and headers.
	 *
	 * @param string $uri The URI to send the request to.
	 * @param array $data An array of data to include in the request body.
	 * @param array $headers An array of headers to include in the request.
	 *
	 * @return mixed The response from the request.
	 */
	public function post( $uri, array $data = [], array $headers = [] ) {
		return $this->request( 'POST', $uri, $data, $headers );
	}
}
