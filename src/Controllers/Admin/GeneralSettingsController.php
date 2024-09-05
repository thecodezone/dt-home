<?php

namespace DT\Home\Controllers\Admin;

use DT\Home\GuzzleHttp\Psr7\ServerRequest as Request;
use function DT\Home\get_plugin_option;
use function DT\Home\set_plugin_option;
use function DT\Home\view;
use function DT\Home\redirect;

class GeneralSettingsController {
	/**
	 * Show the general settings admin tab
	 */
	public function show( Request $request ) {
		$tab                   = "general";
		$link                  = 'admin.php?page=dt_home&tab=';
		$page_title            = "Home Settings";
		$dt_home_require_login = get_plugin_option( 'require_login' );

		return view( "settings/general", compact( 'tab', 'link', 'page_title', 'dt_home_require_login' ) );
	}

	public function update( Request $request ) {
        $input = $request->getParsedBody();
		$require_user = $input['dt_home_require_login'] ?? 'off';

		set_plugin_option( 'require_login', $require_user === 'on' );

		$redirect_url = add_query_arg( 'message', 'updated', admin_url( 'admin.php?page=dt_home' ) );

		return redirect( $redirect_url );
	}
}
