<?php

namespace DT\Home\Services;

use DT\Home\CodeZone\WPSupport\Router\ResponseRendererInterface;
use DT\Home\Psr\Http\Message\ResponseInterface;
use function DT\Home\namespace_string;

/**
 * Class Template
 *
 * This class represents a template in a web application. It is responsible for rendering the template and managing assets.
 */
class Template implements ResponseRendererInterface
{

	/**
	 * @var Assets
	 */
	protected $assets;

	public function __construct( Assets $assets )
	{
		$this->assets = $assets;
	}

	/**
	 * Registers actions and filters for the Blank class.
	 *
	 * @return void
	 */
	public function register() {
		add_filter( 'dt_blank_access', [ $this, 'blank_access' ] );
		add_action( 'dt_blank_head', [ $this, 'header' ] );
		add_action( 'dt_blank_footer', [ $this, 'footer' ] );
		add_filter( namespace_string( 'response_renderer' ), function () {
			return $this;
		} );
		$this->assets->enqueue();
	}

	/**
	 * Allow access to blank template
	 * @return bool
	 */
	public function blank_access(): bool {
		return true;
	}

	/**
	 * Render the header
	 */
	public function header() {
		wp_head();
	}

	/**
	 * Renders a template and stops the script execution.
	 *
	 * @param ResponseInterface $response The response object to render.
	 *
	 * @return void
	 */
	public function render( ResponseInterface $response ) {
		add_action( 'dt_blank_body', function () use ( $response ) {
			// phpcs:ignore
			echo $response->getBody();
		}, 11 );

		$path = get_theme_file_path( 'template-blank.php' );
		include $path;

		die();
	}

	/**
	 * Render the footer
	 * @return void
	 */
	public function footer() {
		wp_footer();
	}
}
