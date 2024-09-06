<?php

/**
 * @var $config DT\Home\CodeZone\WPSupport\Config\ConfigInterface
 */

use DT\Home\MagicLinks\Launcher;

$config->merge( [
    'magic' => [
        'links' => [
            Launcher::class
        ]
    ]
] );
