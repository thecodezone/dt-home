<?php

namespace DT\Home\Apps;

use function DT\Home\template;

class BiblePlugin extends App {

	public function config(): array {
		return [
			"name" => "Bible",
		    "type" => "custom",
		    "icon" => "/wp-content/themes/disciple-tools-theme/dt-assets/images/bible.svg",
		    "sort" => 0,
		    "slug" => "the-bible-plugin",
		    "is_hidden" => false,
		];
	}

	public function template() {
		$html = do_shortcode( '[tbp-bible]' );

		return template('app', [
			'html' => $html
		]);
	}

	public function authorized(): bool {
		if ( !is_plugin_active( 'bible-plugin/bible-plugin.php' ) ) {
			return false;
		}

		return true;
	}
}
