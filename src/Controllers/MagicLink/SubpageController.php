<?php

namespace DT\Launcher\Controllers\MagicLink;

use DT\Launcher\Illuminate\Http\Request;
use DT\Launcher\Illuminate\Http\Response;
use function DT\Launcher\magic_url;
use function DT\Launcher\template;

class SubpageController {
	public function show( Request $request, Response $response, $key ) {
		$user     = wp_get_current_user();
		$home_url = magic_url( '', $key );

		return template( 'subpage', compact(
			'user',
			'home_url'
		) );
	}
}
