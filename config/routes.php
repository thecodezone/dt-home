<?php

/**
 * @var $config DT\Home\CodeZone\WPSupport\Config\ConfigInterface
 */

use function DT\Home\routes_path;

$config->merge( [
    'routes' => [
        'rewrites' => [
            '^apps/?$' => 'index.php?dt-home=/',
            '^apps/(.+)/?' => 'index.php?dt-home=$matches[1]',
        ],
        'files' => [
            'web' => [
                "file" => "web.php",
                'query' => 'dt-home',
                'path' => 'apps',
            ]
        ],
        'middleware' => [
            // CustomMiddleware::class,
        ],
    ],
] );
