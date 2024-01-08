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
		$tab        = "general";
		$link       = 'admin.php?page=dt_launcher&tab=';
		$page_title = "Launcher Settings";

		return view( "settings/general", compact( 'tab', 'link', 'page_title' ) );
	}

	/**
	 * Submit the general settings admin tab form
	 */
	public function update( Request $request, Response $response ) {

		// Add the settings update code here

		return new RedirectResponse( 302, admin_url( 'admin.php?page=dt_launcher&tab=general&updated=true' ) );
		exit();

	}

	public function update_user_access_settings() {
		
			$is_user_logged_in = isset($_POST['require_user']) ? true : false;

			update_option('is_user_logged_in', $is_user_logged_in);
			
			$redirect_url = add_query_arg('message', 'updated', admin_url('admin.php?page=dt_launcher'));
	
			return new RedirectResponse($redirect_url);

			exit();
	}
}
