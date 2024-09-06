<?php

/**
 * @var $config DT\Home\CodeZone\WPSupport\Config\ConfigInterface
 */

use DT\Home\Providers\AdminServiceProvider;
use DT\Home\Providers\AppServiceProvider;
use DT\Home\Providers\MagicLinkServiceProvider;
use DT\Home\Providers\MiddlewareServiceProvider;
use DT\Home\Providers\OptionsServiceProvider;
use DT\Home\Providers\RouteServiceProvider;
use DT\Home\Providers\ViewServiceProvider;
use DT\Home\Providers\AssetServiceProvider;

$config->merge( [
    'services' => [
        'providers' => [
            AdminServiceProvider::class,
            MagicLinkServiceProvider::class,
            AppServiceProvider::class,
            MiddlewareServiceProvider::class,
            OptionsServiceProvider::class,
            RouteServiceProvider::class,
            AssetServiceProvider::class,
            ViewServiceProvider::class,
        ],
        'tgmpa' => [
            'plugins' => [
                [
                    'name'     => 'Disciple.Tools Dashboard',
                    'slug'     => 'disciple-tools-dashboard',
                    'source'   => 'https://github.com/DiscipleTools/disciple-tools-dashboard/releases/latest/download/disciple-tools-dashboard.zip',
                    'required' => false,
                ],
                [
                    'name'     => 'Disciple.Tools Genmapper',
                    'slug'     => 'disciple-tools-genmapper',
                    'source'   => 'https://github.com/DiscipleTools/disciple-tools-genmapper/releases/latest/download/disciple-tools-genmapper.zip',
                    'required' => true,
                ],
                [
                    'name'     => 'Disciple.Tools Autolink',
                    'slug'     => 'disciple-tools-autolink',
                    'source'   => 'https://github.com/DiscipleTools/disciple-tools-genmapper/releases/latest/download/disciple-tools-autolink.zip',
                    'required' => true,
                ]
            ],
            'config' => [
                'id'           => 'disciple_tools',
                'default_path' => '/partials/plugins/',
                'menu'         => 'tgmpa-install-plugins',
                'parent_slug'  => 'plugins.php',
                'capability'   => 'manage_options',
                'has_notices'  => true,
                'dismissable'  => true,
                'dismiss_msg'  => 'These are recommended plugins to complement your Disciple.Tools system.',
                'is_automatic' => true,
                'message'      => '',
            ],
        ]
    ]
]);
