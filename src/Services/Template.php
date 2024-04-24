<?php

namespace DT\Home\Services;

use DT\Home\CodeZone\Router;
use DT\Home\Illuminate\Http\Response;
use function DT\Home\view;

/**
 * Class Template
 *
 * This class represents a template for rendering views.
 */
class Template {
	/**
	 * Class constructor
	 *
	 * @param Assets $assets The Assets object to be injected
	 */
	public function __construct( private Assets $assets ) {}

	/**
	 * Check if blank access is allowed
	 *
	 * @return bool True if blank access is allowed, false otherwise
	 */
	public function blank_access(): bool {
		return true;
	}


	/*
	 * Render the header
	 * @return void
	 */
	/**
	 * Output the HTML head section
	 *
	 * @return void
	 */
	public function header() {
		wp_head();
	}

	/**
	 * Render the specified template with the given data.
	 *
	 * This method executes various actions and filters before rendering the template.
	 * It adds an action for the 'render' namespace, adds a filter for 'dt_blank_access',
	 * and adds actions for 'dt_blank_head' and 'dt_blank_footer'. It also enqueues the assets.
	 *
	 * @param string $template The template file to render.
	 * @param array $data The data to pass to the template.
	 *
	 * @return string The rendered template.
	 */
	public function render( $template, $data ) {
		add_action( Router\namespace_string( 'render' ), [ $this, 'render_response' ], 10, 2 );
		add_filter( 'dt_blank_access', [ $this, 'blank_access' ], 11 );
		add_action( 'dt_blank_head', [ $this, 'header' ], 11 );
		add_action( 'dt_blank_footer', [ $this, 'footer' ], 11 );
		$this->assets->enqueue();

		return view()->render( $template, $data );
	}

	/**
	 * Render the specified response.
	 *
	 * This method handles rendering the response object by either echoing its content
	 * or sending it directly, depending on the value of the 'dt_blank_access' filter.
	 * If the filter returns true, the response content is echoed within a 'dt_blank_body'
	 * action. Otherwise, the response is sent directly.
	 *
	 * @param Response $response The response object to render.
	 */
	public function render_response( Response $response ) {
		if ( apply_filters( 'dt_blank_access', false ) ) {
			add_action( 'dt_blank_body', function () use ( $response ) {
				// phpcs:ignore
				echo $response->getContent();
			}, 11 );
		} else {
			$response->send();
		}
	}

	/**
	 * Adds the WordPress footer to the rendered template.
	 *
	 * This method calls the WordPress function `wp_footer()` which adds the necessary code
	 * for the WordPress footer to be displayed in the rendered template.
	 *
	 * @return void
	 */
	public function footer() {
		wp_footer();
	}
}
