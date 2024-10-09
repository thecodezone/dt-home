<?php

namespace DT\Home\Providers;

use DT\Home\Apps\Autolink;
use DT\Home\Apps\BiblePlugin;
use DT\Home\Apps\DiscipleTools;
use DT\Home\Apps\ThreeThirdsMeetings;
use DT\Home\Conditions\Plugin as IsPlugin;
use DT\Home\League\Container\ServiceProvider\AbstractServiceProvider;
use DT\Home\League\Container\ServiceProvider\BootableServiceProviderInterface;
use DT\Home\Services\MagicApps;

class AppServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{
    protected $apps = [
        Autolink::class,
        BiblePlugin::class,
        ThreeThirdsMeetings::class,
        DiscipleTools::class,
    ];

    public function boot(): void
    {
        $this->getContainer()->addShared( MagicApps::class );
        $this->getContainer()->get( MagicApps::class );

        foreach ( $this->apps as $app ) {
            $this->getContainer()->add( $app );
            $this->getContainer()->get( $app );
        }
    }

    public function provides( string $id ): bool
    {
        return false;
    }

    public function register(): void
    {
        // TODO: Implement register() method.
    }
}
