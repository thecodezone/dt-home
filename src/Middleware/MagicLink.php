<?php

namespace DT\Launcher\Middleware;

use DT\Launcher\CodeZone\Router\Middleware\Middleware;
use DT\Launcher\Illuminate\Http\Request;
use DT\Launcher\Symfony\Component\HttpFoundation\Response;
use DT_Magic_Url_Base;
use function DT\Launcher\container;

/**
 * Check if the current path is a magic link path.
 */
class MagicLink implements Middleware {
	protected DT_Magic_Url_Base $magic_link;

	/**
	 * Construct a new instance of the class.
	 *
	 * @param DT_Magic_Url_Base|string $magic_link The magic link instance or the class name.
	 *
	 * @return void
	 */
	public function __construct( $magic_link ) {
		if ( is_string( $magic_link ) ) {
			$magic_link = container()->make( $magic_link );
		}
		$this->magic_link = $magic_link;
	}

	public function handle( Request $request, Response $response, callable $next ) {
		if ( ! $this->magic_link || ! $this->magic_link->check_parts_match() ) {
			$response->setStatusCode( 404 );
		}

		return $next( $request, $response );
	}
}