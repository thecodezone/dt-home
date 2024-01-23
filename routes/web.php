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
use DT\Launcher\Controllers\Admin\AppSettingsController;
use DT\Launcher\Controllers\Admin\GeneralSettingsController;
use DT\Launcher\Controllers\Admin\TrainingSettingsController;
use DT\Launcher\Controllers\MagicLink\HomeController;
use DT\Launcher\Controllers\MagicLink\TrainingController;
use DT\Launcher\Controllers\MagicLink\ShareController;
use DT\Launcher\Controllers\MagicLink\SubpageController;
use DT\Launcher\Controllers\RedirectController;
use DT\Launcher\Controllers\UserController;
use DT\Launcher\Illuminate\Http\Request;
use DT\Launcher\Symfony\Component\HttpFoundation\Response;

$r->condition( 'plugin', function ( Routes $r ) {
	$r->get( 'launcher', [ RedirectController::class, 'show', [ 'middleware' => 'auth' ] ] );

	$r->group( 'launcher', function ( Routes $r ) {
		$r->get( '/users/{id}', [ UserController::class, 'show', [ 'middleware' => [ 'auth', 'can:list_users' ] ] ] );
		$r->get( '/me', [ UserController::class, 'current', [ 'middleware' => 'auth' ] ] );
		$r->get( '/login', [ UserController::class, 'login', [ 'middleware' => 'guest' ] ] );
		$r->post( '/login', [ UserController::class, 'login_process', [ 'middleware' => 'guest' ] ] );
		$r->get( '/register', [ UserController::class, 'register' ] );
		$r->post( '/register', [ UserController::class, 'register_process' ] );
	} );

  $r->middleware( [ 'magic:launcher/app', 'check_share' ], function ( Routes $r ) {
      $r->group( 'launcher/app/{key}', function ( Routes $r ) {
          $r->get( '', [ HomeController::class, 'show' ] );
          $r->get( '/subpage', [ SubpageController::class, 'show' ] );
          $r->get( '/training', [ TrainingController::class, 'show' ] );
          $r->get( '/{path:.*}', fn( Request $request, Response $response ) => $response->setStatusCode( 404 ) );
      } );
  } );

	$r->middleware( 'magic:launcher/share', function ( Routes $r ) {
		$r->group( 'launcher/share/{key}', function ( Routes $r ) {
			$r->get( '', [ ShareController::class, 'show' ] );
		} );
	} );
} );

$r->condition( 'backend', function ( Routes $r ) {
	$r->middleware( 'can:manage_dt', function ( Routes $r ) {
		$r->group( 'wp-admin/admin.php', function ( Routes $r ) {
			$r->get( '?page=dt_launcher', [ GeneralSettingsController::class, 'show' ] );
			$r->get( '?page=dt_launcher&tab=general', [ GeneralSettingsController::class, 'show' ] );
			$r->get( '?page=dt_launcher&tab=app', [ AppSettingsController::class, 'show_app_tab' ] );
			$r->get( '?page=dt_launcher&tab=app&action=create', [ AppSettingsController::class, 'create_app' ] );
			$r->get( '?page=dt_launcher&tab=app&action=edit/{id}', [ AppSettingsController::class, 'edit_app' ] );
			$r->get( '?page=dt_launcher&tab=app&action=unhide/{id}', [ AppSettingsController::class, 'unhide' ] );
			$r->get( '?page=dt_launcher&tab=app&action=hide/{id}', [ AppSettingsController::class, 'hide' ] );
			$r->get( '?page=dt_launcher&tab=app&action=up/{id}', [ AppSettingsController::class, 'up' ] );
			$r->get( '?page=dt_launcher&tab=app&action=down/{id}', [ AppSettingsController::class, 'down' ] );

            $r->get('?page=dt_launcher&tab=training', [TrainingSettingsController::class, 'show_training_tab']);
            $r->get('?page=dt_launcher&tab=training&action=create', [TrainingSettingsController::class, 'create_training']);
            $r->get('?page=dt_launcher&tab=training&action=edit/{id}', [TrainingSettingsController::class, 'edit_training']);
            $r->get('?page=dt_launcher&tab=training&action=up/{id}', [TrainingSettingsController::class, 'up']);
            $r->get('?page=dt_launcher&tab=training&action=down/{id}', [TrainingSettingsController::class, 'down']);
            $r->get('?page=dt_launcher&tab=training&action=delete/{id}', [TrainingSettingsController::class, 'delete']);

			$r->middleware( 'nonce:dt_admin_form_nonce', function ( Routes $r ) {
				$r->post( '?page=dt_launcher', [ GeneralSettingsController::class, 'update' ] );
				$r->post( '?page=dt_launcher&tab=general', [ GeneralSettingsController::class, 'update' ] );
				$r->post( '?page=dt_launcher&tab=app&action=create', [ AppSettingsController::class, 'store' ] );
				$r->post( '?page=dt_launcher&tab=app&action=edit/{id}', [ AppSettingsController::class, 'update' ] );
                $r->post('?page=dt_launcher&tab=training&action=create', [TrainingSettingsController::class, 'store']);
                $r->post('?page=dt_launcher&tab=training&action=edit/{id}', [TrainingSettingsController::class, 'update']);
            } );
		} );
	} );
} );
