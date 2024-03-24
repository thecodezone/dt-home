<?php

namespace DT\Home\Services;

use function DT\Home\namespace_string;
use function DT\Home\Kucrut\Vite\enqueue_asset;
use function DT\Home\plugin_path;
use function DT\Home\view;

class Template {
	/**
	 * Allow access to blank template
	 * @return bool
	 */
	public function blank_access(): bool {
		return true;
	}

	/**
	 * Reset asset queue
	 * @return void
	 */
	/**
	 * Reset asset queue
	 * @return void
	 */
	private function filter_asset_queue() {
		global $wp_scripts;
		global $wp_styles;

		$whitelist = apply_filters( namespace_string( 'allowed_scripts' ), [] );
		foreach ( $wp_scripts->registered as $script ) {
			if ( in_array( $script->handle, $whitelist ) ) {
				continue;
			}
			wp_dequeue_script( $script->handle );
		}

		$whitelist = apply_filters( namespace_string( 'allowed_styles' ), [] );
		foreach ( $wp_styles->registered as $style ) {
			if ( in_array( $script->handle, $whitelist ) ) {
				continue;
			}
			wp_dequeue_style( $style->handle );
		}
	}

	/**
	 * Start with a blank template
	 * @return void
	 */
	public function template_redirect(): void {
		$path = get_theme_file_path( 'template-blank.php' );
		include $path;
		die();
	}

	/**
	 * Enqueue CSS and JS assets
	 * @return void
	 */
	public function wp_enqueue_scripts(): void {
		$this->filter_asset_queue();

		enqueue_asset(
			plugin_path( '/dist' ),
			'resources/js/plugin.js',
			[
				'handle'    => 'dt_home',
				'css-media' => 'all', // Optional.
				'css-only'  => false, // Optional. Set to true to only load style assets in production mode.
				'in-footer' => false, // Optional. Defaults to false.
			]
		);
	}


	/**
	 * Render the header
	 * @return void
	 */
	public function header() {
		wp_head();
	}

	public function cloak_visibility() {
		?>
        <style>
            .cloak {
                visibility: hidden;
            }
        </style>
		<?php
	}

	/**
	 * Render the template
	 *
	 * @param $template
	 * @param $data
	 *
	 * @return mixed
	 */
	public function render( $template, $data ) {
		add_action( 'template_redirect', [ $this, 'template_redirect' ] );
		add_filter( 'dt_blank_access', [ $this, 'blank_access' ] );
		add_action( 'dt_blank_head', [ $this, 'header' ] );
		add_action( 'dt_blank_footer', [ $this, 'footer' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'wp_enqueue_scripts' ], 1000 );
		add_action( 'wp_head', [ $this, 'cloak_visibility' ] );

		return view()->render( $template, $data );
	}

	/**
	 * Render the footer
	 * @return void
	 */
	public function footer() {
		wp_footer();
	}
}
