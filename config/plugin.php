<?php

/**
 * @var $config DT\Home\CodeZone\WPSupport\Config\ConfigInterface
 */
$config->merge( [
    'plugin' => [
        'text_domain' => 'dt_home',
        'nonce_name' => 'dt_home',
        'dt_version' => 1.19,
        'paths' => [
            'src' => 'src',
            'resources' => 'resources',
            'routes' => 'routes',
            'views' => 'resources/views',
        ]
    ]
]);
