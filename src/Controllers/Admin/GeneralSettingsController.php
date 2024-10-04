<?php

namespace DT\Home\Controllers\Admin;

use DT\Home\GuzzleHttp\Psr7\ServerRequest as Request;
use DT\Home\Psr\Http\Message\ResponseInterface;
use function DT\Home\extract_request_input;
use function DT\Home\get_plugin_option;
use function DT\Home\redirect;
use function DT\Home\set_plugin_option;
use function DT\Home\view;

class GeneralSettingsController {
	/**
	 * Show the general settings admin tab
	 *
	 * @return ResponseInterface
	 */
	public function show( Request $request ) {
		$tab                   = 'general';
		$link                  = 'admin.php?page=dt_home&tab=';
		$page_title            = 'Home Settings';
		$dt_home_require_login = get_plugin_option( 'require_login' );
		$dt_home_reset_apps    = get_plugin_option( 'reset_apps' );

		return view( 'settings/general', compact( 'tab', 'link', 'page_title', 'dt_home_require_login', 'dt_home_reset_apps' ) );
	}

	/**
	 * Update the general settings admin tab
	 *
	 * @param Request $request The Request object containing the parsed body data
	 *
	 * @return ResponseInterface The redirect response
	 */
	public function update( Request $request ) {
		$input        = extract_request_input( $request );
		$require_user = $input['dt_home_require_login'] ?? 'off';
		$reset_apps   = $input['dt_home_reset_apps'] ?? 'off';

		set_plugin_option( 'require_login', $require_user === 'on' );
		set_plugin_option( 'reset_apps', $reset_apps === 'on' );

		$redirect_url = add_query_arg( 'message', 'updated', admin_url( 'admin.php?page=dt_home' ) );

		return redirect( $redirect_url );
	}
}
