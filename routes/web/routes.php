<?php
/**
 * Conditions are used to determine if a group of routes should be registered.
 *
 * Groups are used to register a group of routes with a common URL prefix.
 *
 * Middleware is used to modify requests before they are handled by a controller, or to modify responses before they are returned to the client.
 *
 * Routes are used to bind a URL to a controller.
 *
 * @var Routes $r
 * @see https://github.com/thecodezone/wp-router
 */

use DT\Launcher\CodeZone\Router\FastRoute\Routes;
use DT\Launcher\Controllers\Admin\GeneralSettingsController;
use DT\Launcher\Controllers\HelloController;
use DT\Launcher\Controllers\StarterMagicLink\HomeController;
use DT\Launcher\Controllers\StarterMagicLink\SubpageController;
use DT\Launcher\Controllers\UserController;

$r->condition( 'Launcher', function ( $r ) {
	$r->group( 'dt/Launcher', function ( Routes $r ) {
		$r->get( '', [ RedirectController::class, 'show' ] );
		$r->get( '/users/{id}', [ UserController::class, 'show', [ 'middleware' => [ 'auth', 'can:list_users' ] ] ] );
		$r->get( '/me', [ UserController::class, 'current', [ 'middleware' => 'auth' ] ] );
	} );

	$r->group( 'dt/plugin/api', function ( Routes $r ) {
		$r->get( '/hello', [ HelloController::class, 'show' ] );
	} );
} );

$r->condition( 'backend', function ( Routes $r ) {
	$r->middleware( 'can:manage_dt', function ( Routes $r ) {
		$r->group( 'wp-admin/admin.php', function ( Routes $r ) {
			$r->get( '?page=dt_plugin', [ GeneralSettingsController::class, 'show' ] );
			$r->get( '?page=dt_plugin&tab=general', [ GeneralSettingsController::class, 'show' ] );

			$r->middleware( 'nonce:dt_admin_form_nonce', function ( Routes $r ) {
				$r->post( '?page=dt_plugin', [ GeneralSettingsController::class, 'update' ] );
				$r->post( '?page=dt_plugin&tab=general', [ GeneralSettingsController::class, 'update' ] );
			} );
		} );
	} );
} );

$r->middleware( 'magic:starter/app', function ( Routes $r ) {
	$r->group( 'starter/app/{key}', function ( Routes $r ) {
		$r->get( '', [ HomeController::class, 'show' ] );
		$r->get( '/subpage', [ SubpageController::class, 'show' ] );
	} );
} );

