<?php
/**
 * @var RouteCollector $r
 */

use DT\Launcher\Controllers\HelloController;
use DT\Launcher\FastRoute\RouteCollector;

$r->get( 'dt/launcher/hello', HelloController::class . '@show' );
