<?php

namespace DT\Launcher\Controllers\UserMagicLInk;

use DT\Launcher\MagicLinks\UserMagicLink;
use WP_REST_Response;
use function DT\Launcher\view;

class UserMagicLinkController {

	public function __construct( UserMagicLink $magic_link ) {
		$this->magic_link = $magic_link;
	}

	public function show() {
		$user        = wp_get_current_user();
		$subpage_url = $this->magic_link->url . '?' . http_build_query( [
				'page' => 'subpage'
        ] );

		view( 'user-magic-link/show', compact(
			'user',
			'subpage_url'
		) );
	}

	public function data() {
		$user = wp_get_current_user();
		$data = [
			'user_login' => $user->user_login,
		];

		return new WP_REST_Response( $data, 200 );
	}
}
