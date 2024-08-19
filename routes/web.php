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

use DT\Home\CodeZone\Router\FastRoute\Routes;
use DT\Home\Controllers\Admin\AppSettingsController;
use DT\Home\Controllers\Admin\GeneralSettingsController;
use DT\Home\Controllers\Admin\TrainingSettingsController;
use DT\Home\Controllers\AppController;
use DT\Home\Controllers\LoginController;
use DT\Home\Controllers\MagicLink\HomeController;
use DT\Home\Controllers\MagicLink\ShareController;
use DT\Home\Controllers\MagicLink\TrainingController;
use DT\Home\Controllers\RedirectController;
use DT\Home\Controllers\RegisterController;
use DT\Home\Illuminate\Http\Request;
use DT\Home\Symfony\Component\HttpFoundation\Response;


$r->get( '/', [ RedirectController::class, 'show' ] );
$r->get( 'login', [ LoginController::class, 'login', [ 'middleware' => 'guest' ] ] );
$r->get( 'register', [ RegisterController::class, 'register', [ 'middleware' => 'guest' ] ] );

$r->middleware('nonce:dt_home', function ( Routes $r ) {
    $r->post( 'login', [ LoginController::class, 'process', [ 'middleware' => 'guest' ] ] );
    $r->post( 'register', [ RegisterController::class, 'process', [ 'middleware' => 'guest' ] ] );
});

$r->middleware('magic:home/launcher', function ( Routes $r ) {
    $r->group('launcher/{key}', function ( Routes $r ) {
        $r->middleware([ 'auth', 'check_share' ], function ( Routes $r ) {
            $r->get( '/app/{slug}', [ AppController::class, 'show' ] );
            $r->get( '', [ HomeController::class, 'show' ] );
            $r->get( '/hidden-apps', [ HomeController::class, 'show_hidden_apps' ] );
            $r->get( '/training', [ TrainingController::class, 'show' ] );
            $r->get( '/get-apps', [ HomeController::class, 'get_apps' ] );


            $r->middleware('nonce:dt_home', function ( Routes $r ) {
                $r->post( '/update-hide-apps', [ HomeController::class, 'update_hide_app' ] );
                $r->post( '/un-hide-app', [ HomeController::class, 'update_unhide_app' ] );
                $r->post( '/update-app-order', [ HomeController::class, 'update_app_order' ] );
            });
            $r->get( '/logout', [ LoginController::class, 'logout' ] );
        });

        $r->get( '/share', [ ShareController::class, 'show' ] );

        $r->get( '/{path:.*}', fn( Request $request, Response $response ) => $response->setStatusCode( 404 ) );
    });
});

$r->condition('backend', function ( Routes $r ) {
    $r->middleware('can:manage_dt', function ( Routes $r ) {
        $r->group('wp-admin/admin.php', function ( Routes $r ) {
            $r->get( '?page=dt_home', [ GeneralSettingsController::class, 'show' ] );
            $r->get( '?page=dt_home&tab=general', [ GeneralSettingsController::class, 'show' ] );

            $r->get( '?page=dt_home&tab=app', [ AppSettingsController::class, 'show_app_tab' ] );
            $r->get( '?page=dt_home&tab=app&action=create', [ AppSettingsController::class, 'create_app' ] );
            $r->get( '?page=dt_home&tab=app&action=edit/{slug}', [ AppSettingsController::class, 'edit_app' ] );
            $r->get( '?page=dt_home&tab=app&action=unhide/{slug}', [ AppSettingsController::class, 'unhide' ] );
            $r->get( '?page=dt_home&tab=app&action=hide/{slug}', [ AppSettingsController::class, 'hide' ] );
            $r->get( '?page=dt_home&tab=app&action=up/{slug}', [ AppSettingsController::class, 'up' ] );
            $r->get( '?page=dt_home&tab=app&action=down/{slug}', [ AppSettingsController::class, 'down' ] );
            $r->get( '?page=dt_home&tab=app&action=delete/{slug}', [ AppSettingsController::class, 'delete' ] );

            $r->get( '?page=dt_home&tab=training', [ TrainingSettingsController::class, 'show_training_tab' ] );
            $r->get('?page=dt_home&tab=training&action=create', [
                TrainingSettingsController::class,
                'create_training'
            ]);
            $r->get('?page=dt_home&tab=training&action=edit/{id}', [
                TrainingSettingsController::class,
                'edit_training'
            ]);
            $r->get( '?page=dt_home&tab=training&action=up/{id}', [ TrainingSettingsController::class, 'up' ] );
            $r->get( '?page=dt_home&tab=training&action=down/{id}', [ TrainingSettingsController::class, 'down' ] );
            $r->get('?page=dt_home&tab=training&action=delete/{id}', [
                TrainingSettingsController::class,
                'delete'
            ]);

            $r->middleware('nonce:dt_admin_form_nonce', function ( Routes $r ) {
                $r->post( '?page=dt_home', [ GeneralSettingsController::class, 'update' ] );
                $r->post( '?page=dt_home&tab=general', [ GeneralSettingsController::class, 'update' ] );
                $r->post( '?page=dt_home&tab=app&action=create', [ AppSettingsController::class, 'store' ] );
                $r->post( '?page=dt_home&tab=app&action=edit/{slug}', [ AppSettingsController::class, 'update' ] );
                $r->post('?page=dt_home&tab=training&action=create', [
                    TrainingSettingsController::class,
                    'store'
                ]);
                $r->post('?page=dt_home&tab=training&action=edit/{id}', [
                    TrainingSettingsController::class,
                    'update'

                ]);
            });
        });
    });
});
