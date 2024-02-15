<?php

namespace DT\Home\Providers;

use DT\Home\MagicLinks\Launcher;
use DT\Home\MagicLinks\Share;
use function DT\Home\collect;

class MagicLinkServiceProvider extends ServiceProvider
{
    protected $container;

    protected $magic_links = [
        'home/launcher' => Launcher::class,
        'home/share' => Share::class,
    ];

    /**
     * Do any setup needed before the theme is ready.
     * DT is not yet registered.
     */
    public function register(): void
    {
        $this->container->bind('DT\Home\MagicLinks', function () {
            return collect( $this->magic_links );
        });
    }

    /**
     * Do any setup after services have been registered and the theme is ready
     */
    public function boot(): void
    {
        $this->container->make( 'DT\Home\MagicLinks' )
            ->each(function ( $magic_link ) {
                $this->container->singleton( $magic_link );
                $this->container->make( $magic_link );
            });
    }
}
