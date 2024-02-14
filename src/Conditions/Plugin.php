<?php

namespace DT\Home\Conditions;

use DT\Home\CodeZone\Router\Conditions\Condition;
use DT\Home\Illuminate\Http\Request;
use DT\Home\Illuminate\Support\Str;
use DT\Home\Plugin as Main;

class Plugin implements Condition {

	/**
	 * @var Request $request The current request object.
	 */
	protected Request $request;

	/**
	 * __construct
	 *
	 * This method is the constructor for the class. It initializes the class instance with a Request object.
	 *
	 * @param Request $request The Request object to be used by the class instance
	 */
	public function __construct( Request $request ) {
		$this->request = $request;
	}

	/**
	 * Determine if the current request path starts with the home route.
	 *
	 * @return bool Returns true if the current request path starts with the home route, false otherwise.
	 */
	public function test(): bool {
		return Str::startsWith( $this->request->path(), Main::HOME_ROUTE );
	}
}