<?php

/**
 * @var RouteCollectionInterface $r
 * @var \DT\Home\MagicLinks\AppJson $this
 * @see https://route.thephpleague.com/
 */

use DT\Home\League\Route\RouteCollectionInterface;
use DT\Home\Controllers\MagicLink\AppJsonController;

$r->group('/apps/json', function ( RouteCollectionInterface $r ) {
    $r->get( '/', [ AppJsonController::class, 'index' ] );
});
