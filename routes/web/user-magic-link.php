<?php

use DT\Launcher\Controllers\UserMagicLInk\UserMagicLinkController;
use DT\Launcher\Controllers\UserMagicLInk\UserMagicLinkSubpageController;
use DT\Launcher\MagicLinks\UserMagicLink;
use function DT\Launcher\container;

$container  = container();
$magic_link = $container->make( UserMagicLink::class );

$r->get( $magic_link->path, UserMagicLinkController::class . '@show' );
$r->get( $magic_link->path . '?page=subpage', UserMagicLinkSubpageController::class . '@show' );
