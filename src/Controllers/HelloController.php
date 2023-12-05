<?php

namespace DT\Launcher\Controllers;

use WP_REST_Response;

class HelloController {
	/**
	 * Show the hello world message
	 *
	 * @return WP_REST_Response
	 */
	public function data() {
		return new WP_REST_Response( [
			'status'  => 'success',
			'message' => 'Hello World!'
		], 200 );
	}

	public function show() {
		$name = 'Friend';
		include __DIR__ . '/../../resources/templates/hello.php';
	}
}
