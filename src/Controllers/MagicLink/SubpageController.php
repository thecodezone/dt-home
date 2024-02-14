<?php

namespace DT\Home\Controllers\MagicLink;

use DT\Home\Illuminate\Http\Request;
use DT\Home\Illuminate\Http\Response;
use function DT\Home\magic_url;
use function DT\Home\template;

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
