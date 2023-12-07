<?php

namespace DT\Launcher;

use DT\Launcher\Illuminate\Support\Str;
use DT\Launcher\League\Plates\Engine;
use DT\Launcher\Services\Template;

function plugin() {
	return Plugin::$instance;
}

function container() {
	return plugin()->container;
}


function plugin_path( $path = '' ) {
	return '/' . implode( '/', [
			trim( Str::remove( '/src', plugin_dir_path( __FILE__ ) ), '/' ),
			trim( $path, '/' ),
		] );
}

function src_path( $path = '' ) {
	return plugin_path( 'src/' . $path );
}

function resources_path( $path = '' ) {
	return plugin_path( 'resources/' . $path );
}

function routes_path( $path = '' ) {
	return plugin_path( 'routes/' . $path );
}

function views_path( $path = '' ) {
	return plugin_path( 'resources/views/' . $path );
}

function view( $view = "", $args = [] ) {
	$engine = container()->make( Engine::class );
	if ( ! $view ) {
		return $engine;
	}

	// phpcs:ignore
	echo $engine->render( $view, $args );
}

function template( $template = "", $args = [] ) {
	$service = container()->make( Template::class );
	if ( ! $template ) {
		return $service;
	}

	return $service->render( $template, $args );
}
