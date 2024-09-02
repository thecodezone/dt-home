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
 * @var RouteCollectionInterface $r
 * @see https://github.com/thecodezone/wp-router
 */

use DT\Home\Controllers\LoginController;
use DT\Home\Controllers\RedirectController;
use DT\Home\Controllers\RegisterController;
use DT\Home\League\Route\RouteCollectionInterface;
use DT\Home\Middleware\LoggedOut;
use DT\Home\CodeZone\WPSupport\Middleware\Nonce;
use function DT\Home\config;

$r->get( '/', [ RedirectController::class, 'show' ] );
$r->get( '/login', [ LoginController::class, 'login' ] )->middleware( new LoggedOut() );
$r->get( '/register', [ RegisterController::class, 'register' ] )->middleware( new LoggedOut() );

$r->group('', function ( RouteCollectionInterface $r ) {
    $r->post( '/login', [ LoginController::class, 'process' ] )->middleware( new LoggedOut() );
    $r->post( '/register', [ RegisterController::class, 'process' ] )->middleware( new LoggedOut() );
})->middleware( new Nonce( config( 'plugin.nonce_name' ) ) );
