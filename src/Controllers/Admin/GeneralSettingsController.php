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
	
		if (isset($_POST['dt_admin_form_nonce']) && wp_verify_nonce($_POST['dt_admin_form_nonce'], 'dt_admin_form')) {

			if (isset($_POST['require_user'])) {
				update_option('is_user_logged_in', true);
			} else {
				update_option('is_user_logged_in', false); 

			}
		}

		wp_redirect(admin_url('admin.php?page=dt_launcher'));

		exit();
	}
}
