<?php

namespace DT\Launcher\Controllers\Admin;

use DT\Launcher\Illuminate\Http\RedirectResponse;
use DT\Launcher\Illuminate\Http\Request;
use DT\Launcher\Illuminate\Http\Response;
use function DT\Launcher\view;

class GeneralSettingsController {
	/**
	 * Show the general settings admin tab
	 */
	public function show( Request $request, Response $response ) {
		$tab                       = "general";
		$link                      = 'admin.php?page=dt_launcher&tab=';
		$page_title                = "Launcher Settings";
		$dt_launcher_require_login = get_option( 'dt_launcher_require_login', true );

		return view( "settings/general", compact( 'tab', 'link', 'page_title', 'dt_launcher_require_login' ) );
	}

	public function update( Request $request, Response $response ) {
		$require_user = $request->input( 'dt_launcher_require_login', false );

		update_option( 'dt_launcher_require_login', $require_user === 'on' );

		$redirect_url = add_query_arg( 'message', 'updated', admin_url( 'admin.php?page=dt_launcher' ) );

		return new RedirectResponse( $redirect_url );
	}
}
