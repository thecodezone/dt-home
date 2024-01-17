<?php

namespace DT\Launcher\Providers;

use DT\Launcher\MagicLinks\App;
use DT\Launcher\MagicLinks\Share;
use function DT\Launcher\collect;

class MagicLinkServiceProvider extends ServiceProvider
{
    protected $container;

    protected $magic_links = [
        'launcher/app' => App::class,
        'launcher/share' => Share::class,
    ];

    /**
     * Do any setup needed before the theme is ready.
     * DT is not yet registered.
     */
    public function register(): void
    {
        $this->container->bind('DT\Launcher\MagicLinks', function () {
            return collect($this->magic_links);
        });
    }

    /**
     * Do any setup after services have been registered and the theme is ready
     */
    public function boot(): void
    {
        $this->container->make('DT\Launcher\MagicLinks')
            ->each(function ($magic_link) {
                $this->container->singleton($magic_link);
                $this->container->make($magic_link);
            });
    }
}
