<?php

namespace DT\Home\Controllers\MagicLink;

use DT\Home\Psr\Http\Message\ResponseInterface;
use DT\Home\Psr\Http\Message\ServerRequestInterface as Request;
use DT\Home\Services\Apps;
use function DT\Home\container;
use function DT\Home\get_plugin_option;
use function DT\Home\magic_url;
use function DT\Home\template;

/**
 *
 */
class LauncherController {
	/**
	 * Shows the index page with user data and magic link.
	 *
	 * @param Request $request The request object.
	 * @param mixed   $params The parameters containing the key.
	 *
	 * @return ResponseInterface The response containing the rendered template.
	 */
	public function show( Request $request, $params ) {
		$key         = $params['key'];
		$apps        = container()->get( Apps::class );
		$user        = get_current_user_id();
		$apps_array  = $apps->for_user( $user );
		$data        = json_encode( $apps_array );
		$hidden_data = json_encode( $apps_array );
		$app_url     = magic_url( '', $key );
		$magic_link  = $app_url . '/share';
		$reset_apps  = get_plugin_option( 'reset_apps' );
        $button_color  = get_plugin_option( 'button_color' );
		return template(
			'index',
			compact(
				'user',
				'data',
				'app_url',
				'magic_link',
				'hidden_data',
				'reset_apps',
                'button_color'
			)
		);
	}
}
