<?php

/**
 * @var $config DT\Plugin\CodeZone\WPSupport\Config\ConfigInterface
 */

use function DT\Home\magic_url;
use function DT\Home\config;
use function DT\Home\plugin_path;
use function DT\Home\route_url;

$config->merge( [
    'assets' => [
        'allowed_styles' => [
            'material-font-icons',
            'material-font-icons-local',
            'dt-home',
        ],
        'allowed_scripts' =>[
            'dt-home',
        ],
        'javascript_global_scope' => '$home',
        'javascript_globals' => [
            'translations' => [
                'remove_app_confirmation' => __( 'Are you sure you want to remove this app?', 'dt-home' ),
                'installAppLabel' => 'Install as App',
                'hiddenAppLabel' => 'Hidden Apps',
                'buttonLabel' => 'Ok',
            ]
        ],
        'manifest_dir' => plugin_path( '/dist' )
    ]
] );



add_action('wp_loaded', function () use ( $config ) {
    $config->set( 'assets.javascript_globals', [
        'nonce' => wp_create_nonce( 'dt_home' ),
        'admin_nonce' => wp_create_nonce( 'dt_admin_form_nonce' ),
        'route_url' => route_url(),
        'magic_url' => magic_url(),
    ]);
});
