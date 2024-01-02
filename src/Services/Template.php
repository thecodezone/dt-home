<?php

namespace DT\Launcher\Services;

use function DT\Launcher\Kucrut\Vite\enqueue_asset;
use function DT\Launcher\plugin_path;
use function DT\Launcher\views_path;
use function DT\Launcher\view;

class Template {

	/**
	 * Allow access to blank template
	 * @return bool
	 */
	public function blank_access(): bool {
		return true;
	}

	/**
	 * Start with a blank template
	 * @return void
	 */

	// public function template_redirect(): void {
	// 	// You can set up data to be passed to the view here if needed
	// 	$data = [];
	
	// 	// Output WordPress header
	// 	$this->header();
	
	// 	// Output the template content
	// 	$this->render('index.php', $data);
	
	// 	// Output WordPress footer
	// 	$this->footer();
	
	// 	// Terminate the script
	// 	die();
	// }

	// public function template_redirect(): void {
	// 	$path = views_path( 'index.php' );
	// 	include $path;
	// 	return;
	// }

	/**
	 * Render the header
	 * @return void
	 */
	public function header() {
		wp_head();
	}

	/**
	 * Render the template
	 *
	 * @param $callback
	 *
	 * @return void
	 */

	public function render($template, $data): void {
		// Assuming view() is a function that includes your template
		$this->header();
		echo view($template, $data);
		$this->footer();
		die();  // You might want to remove this line depending on your needs
	}

	// public function render( $template, $data ) {
	// 	add_action( 'template_redirect', [ $this, 'template_redirect' ] );
	// 	add_filter( 'dt_blank_access', [ $this, 'blank_access' ] );
	// 	add_action( 'dt_blank_head', [ $this, 'header' ] );
	// 	add_action( 'dt_blank_footer', [ $this, 'footer' ] );
		
	// 	add_action( 'dt_blank_body', function () use ( $template, $data ) {
	// 		// phpcs:ignore
	// 		dd($template);
	// 		echo view()->render( $template, $data );
	// 	} );
	// }

	/**
	 * Render the footer
	 * @return void
	 */
	public function footer() {
		wp_footer();
	}
}
