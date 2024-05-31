<?php

namespace DT\Home\Apps;

use function DT\Home\get_magic_url;
use function DT\Home\magic_app;
use function DT\Home\is_plugin_active;

class ShareApp extends App {

	public function config(): array {
		return [
			"name" => "Gospel Sharing",
		    "type" => "custom",
		    "icon" => "/wp-content/themes/disciple-tools-theme/dt-assets/images/cross.svg",
		    "sort" => 0,
		    "slug" => "disciple-tools-share-app",
		    "is_hidden" => false,
		];
	}

	public function authorized(): bool {
		if ( !is_user_logged_in() ) {
			return false;
		}

		if ( !is_plugin_active( 'disciple-tools-share-app/disciple-tools-share-app.php' ) ) {
			return false;
		}

		if ( ! magic_app( 'share_app', 'ofc' ) ) {
			return false;
		}

		return true;
	}

	public function url(): string {
		return get_magic_url( 'share_app', 'ofc', \Disciple_Tools_Users::get_contact_for_user( get_current_user_id() ) );
	}
}
