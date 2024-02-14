<?php

namespace DT\Home\Services\Plates;

use DT\Home\League\Plates\Engine;
use DT\Home\League\Plates\Extension\ExtensionInterface;

class Escape implements ExtensionInterface {


	/**
	 * Register functions with the given Engine.
	 *
	 * @param Engine $engine The Engine instance to register the functions with.
	 *
	 * @return void
	 */
	public function register( Engine $engine ) {
		$engine->registerFunction( 'esc_html_e', 'esc_html_e' );
		$engine->registerFunction( 'esc_html', 'esc_html' );
		$engine->registerFunction( 'esc_attr_e', 'esc_attr_e' );
		$engine->registerFunction( 'esc_attr', 'esc_attr' );
		$engine->registerFunction( 'esc_url', 'esc_url' );
	}
}
