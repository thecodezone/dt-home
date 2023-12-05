<?php

namespace DT\Launcher\Controllers\Admin;

use function DT\Launcher\plugin;

class GeneralSettingsController {
	/**
	 * Show the general settings admin tab
	 * @return void
	 */
	public function show() {
		if ( ! current_user_can( 'manage_dt' ) ) { // manage dt is a permission that is specific to Disciple.Tools and allows admins, strategists and dispatchers into the wp-admin
			wp_die( 'You do not have sufficient permissions to access this page.' );
		}

		$tab        = "general";
		$link       = 'admin.php?page=disciple_tools_autolink&tab=';
		$page_title = "Autolink Settings";
		include plugin()->templates_path . "/settings/general.php";
	}

	/**
	 * Submit the general settings admin tab form
	 * @return void
	 */
	public function update() {
		if ( ! isset( $_POST['dt_admin_form_nonce'] ) &&
		     ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['dt_admin_form_nonce'] ) ), 'dt_admin_form' ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_dt' ) ) { // manage dt is a permission that is specific to Disciple.Tools and allows admins, strategists and dispatchers into the wp-admin
			wp_die( 'You do not have sufficient permissions.' );
		}

		//Do whatever we need to do to update the settings

		wp_redirect( admin_url( 'admin.php?page=disciple_tools_autolink&tab=general&updated=true' ) );
	}
}
