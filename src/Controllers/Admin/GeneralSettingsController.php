<?php

namespace DT\Home\Controllers\Admin;

use DT\Home\Illuminate\Http\RedirectResponse;
use DT\Home\Illuminate\Http\Request;
use DT\Home\Illuminate\Http\Response;
use function DT\Home\view;

class GeneralSettingsController {
	/**
	 * Show the general settings admin tab
	 */
	public function show( Request $request, Response $response ) {
		$tab                   = "general";
		$link                  = 'admin.php?page=dt_home&tab=';
		$page_title            = "Home Settings";
		$dt_home_require_login = get_option( 'dt_home_require_login', true );

		return view( "settings/general", compact( 'tab', 'link', 'page_title', 'dt_home_require_login' ) );
	}

	public function update( Request $request, Response $response ) {
		$require_user = $request->input( 'dt_home_require_login', false );

		update_option( 'dt_home_require_login', $require_user === 'on' );

		$redirect_url = add_query_arg( 'message', 'updated', admin_url( 'admin.php?page=dt_home' ) );

		return new RedirectResponse( $redirect_url );
	}
}
