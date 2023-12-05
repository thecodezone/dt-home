<?php

use DT\Launcher\Controllers\HelloController;
use function DT\Launcher\container;

register_rest_route( 'dt/launcher/v1', 'hello', [
	[
		'methods'             => 'GET',
		'callback'            => [ container()->make( HelloController::class ), 'data' ],
		'permission_callback' => '__return_true',
	]
] );
