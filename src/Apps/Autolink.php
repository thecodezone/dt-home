<?php

namespace DT\Home\Apps;

use function DT\Home\template;

class Autolink extends App {

	public function config(): array {
		return [
			"name" => "Autolink",
		    "type" => "custom",
		    "icon" => "https://discipletools.ddev.site/wp-content/themes/disciple-tools-theme/dt-assets/images/link.svg",
			'url' => '/autolink',
		    "sort" => 0,
		    "slug" => "disciple-tools-autolink",
		    "is_hidden" => false,
		];
	}

	public function authorized(): bool {
		if ( !is_plugin_active( 'disciple-tools-autolink/disciple-tools-autolink.php' ) ) {
			return false;
		}

		return true;
	}
}
