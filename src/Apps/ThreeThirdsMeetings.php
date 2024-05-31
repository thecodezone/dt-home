<?php

namespace DT\Home\Apps;

use function DT\Home\is_plugin_active;

class ThreeThirdsMeetings extends App {
	public function config(): array {
		return [
			"name" => "3/3 Meetings",
		    "type" => "custom",
		    "icon" => "/wp-content/themes/disciple-tools-theme/dt-assets/images/calendar-clock.svg",
			'url' => '/3/3',
		    "sort" => 0,
		    "slug" => "three-thirds-meetings",
		    "is_hidden" => false,
		];
	}

	public function authorized(): bool {
		if ( !is_plugin_active( 'disciple-tools-three-thirds/disciple-tools-three-thirds.php' ) ) {
			return false;
		}

		return true;
	}
}
