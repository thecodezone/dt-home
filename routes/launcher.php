<?php

/**
 * @var RouteCollectionInterface $r
 */

use DT\Home\CodeZone\WPSupport\Middleware\Nonce;
use DT\Home\Controllers\AppController;
use DT\Home\Controllers\LoginController;
use DT\Home\Controllers\MagicLink\HomeController;
use DT\Home\Controllers\MagicLink\ShareController;
use DT\Home\Controllers\MagicLink\TrainingController;
use DT\Home\Middleware\CheckShareCookie;
use DT\Home\Middleware\LoggedIn;
use DT\Home\League\Route\RouteCollectionInterface;
use function DT\Home\config;

$r->group( '', function ( RouteCollectionInterface $r ) {
	$r->get( '/app/{slug}', [ AppController::class, 'show' ] );
	$r->get( '/', [ HomeController::class, 'show' ] );
	$r->get( '/hidden-apps', [ HomeController::class, 'show_hidden_apps' ] );
	$r->get( '/training', [ TrainingController::class, 'show' ] );
	$r->get( '/logout', [ LoginController::class, 'logout' ] );
})->middleware( new LoggedIn(), new CheckShareCookie() );

$r->group( '', function ( RouteCollectionInterface $r ) {
	$r->post( '/update-hide-apps', [ HomeController::class, 'update_hide_app' ] );
	$r->post( '/un-hide-app', [ HomeController::class, 'update_unhide_app' ] );
	$r->post( '/update-app-order', [ HomeController::class, 'update_app_order' ] );
})->middleware( new LoggedIn(), new CheckShareCookie(), new Nonce( config( 'plugin.nonce_name' ) ) );

$r->get( '/share', [ ShareController::class, 'show' ] );
