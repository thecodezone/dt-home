<?php

/**
 * User Magic Link REST API Routes
 *
 * @var string $namespace
 * @var $this UserMagicLink
 */

use DT\Launcher\MagicLinks\UserMagicLink;
use function DT\Launcher\container;

register_rest_route(
	$namespace, '/' . $this->type, [
		[
			'methods'             => 'GET',
			'callback'            => [ container()->make( UserMagicLink::class ), 'data' ],
			'permission_callback' => function ( WP_REST_Request $request ) {
				$magic = new DT_Magic_URL( $this->root );

				return $magic->verify_rest_endpoint_permissions_on_post( $request );
			},
		],
	]
);
