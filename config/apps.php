<?php

/**
 * @var $config DT\Home\CodeZone\WPSupport\Config\ConfigInterface
 */

use DT\Home\Sources\FilterApps;
use DT\Home\Sources\SettingsApps;
use DT\Home\Sources\UserApps;

$config->merge( [
    'apps' => [
        'sources' => [
            FilterApps::class,
            SettingsApps::class,
            UserApps::class
        ],
        'source_handles' => [
            'filter' => FilterApps::class,
            'settings' => SettingsApps::class,
            'user' => UserApps::class
        ]
    ]
] );
