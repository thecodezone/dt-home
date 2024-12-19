<?php

/**
 * @var $config DT\Home\CodeZone\WPSupport\Config\ConfigInterface
 */

$config->merge([
    'options' => [
        'prefix' => 'dt_home',
        'defaults' => [
            'require_login' => true,
            'reset_apps' => false,
            'button_color' => '#3fab3f',
            'apps' => [],
            'trainings' => [],
            'dt_home_plugin_url' => home_url( add_query_arg( null, null ) ),
            'dt_home_analytics_permission' => false,
        ],
    ]
]);
