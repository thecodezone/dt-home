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
use DT\Launcher\Controllers\MagicLink\HomeController;
use DT\Launcher\Controllers\MagicLink\SubpageController;
use DT\Launcher\Controllers\RedirectController;
use DT\Launcher\Controllers\UserController;

$r->condition( 'plugin', function ( Routes $r ) {
	$r->get( 'launcher', [ RedirectController::class, 'show', [ 'middleware' => 'auth' ] ] );

	$r->group( 'launcher', function ( Routes $r ) {
		$r->get( '/users/{id}', [ UserController::class, 'show', [ 'middleware' => [ 'auth', 'can:list_users' ] ] ] );
		$r->get( '/me', [ UserController::class, 'current', [ 'middleware' => 'auth' ] ] );
	} );
	$r->middleware( 'magic:launcher/app', function ( Routes $r ) {
		$r->group( 'launcher/app/{key}', function ( Routes $r ) {
			$r->get( '', [ HomeController::class, 'show' ] );
			$r->get( '/subpage', [ SubpageController::class, 'show' ] );
		} );
	} );
} );

$r->condition( 'backend', function ( Routes $r ) {
	$r->middleware( 'can:manage_dt', function ( Routes $r ) {
		$r->group( 'wp-admin/admin.php', function ( Routes $r ) {
			$r->get( '?page=dt_launcher', [ GeneralSettingsController::class, 'show' ] );
			$r->get( '?page=dt_launcher&tab=general', [ GeneralSettingsController::class, 'show' ] );

			$r->middleware( 'nonce:dt_admin_form_nonce', function ( Routes $r ) {
				$r->post( '?page=dt_launcher', [ GeneralSettingsController::class, 'update' ] );
				$r->post( '?page=dt_launcher&tab=general', [ GeneralSettingsController::class, 'update' ] );
			} );
		} );
	} );
} );
