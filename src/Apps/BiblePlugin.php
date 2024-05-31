<?php

namespace DT\Home\Apps;

use DT\Home\Services\Assets;
use function DT\Home\container;
use function DT\Home\namespace_string;
use function DT\Home\template;
use function DT\Home\is_plugin_active;

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

	public function allowed_scripts( $scripts ) {
		$assets = container()->make( Assets::class );
		$scripts[] = "bible-plugin";

		foreach ( $scripts as $idx => $script ) {
			if ( $assets->is_vite_asset( $script ) ) {
				unset( $scripts[$idx] );
			}
		}

		return $scripts;
	}

	public function allowed_styles( $styles ) {
		$styles[] = "bible-plugin";

		return $styles;
	}

	public function template() {
		$html = do_shortcode( '[tbp-bible]' );

		add_filter( namespace_string( 'allowed_scripts' ), [ $this, 'allowed_scripts' ], 11, 1 );
		add_filter( namespace_string( 'allowed_styles' ), [ $this, 'allowed_styles' ], 9, 1 );

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
