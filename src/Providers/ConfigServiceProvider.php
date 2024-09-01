<?php

namespace DT\Home\Providers;

use DT\Home\CodeZone\WPSupport\Config\Config;
use DT\Home\CodeZone\WPSupport\Config\ConfigInterface;
use DT\Home\League\Container\Exception\NotFoundException;
use DT\Home\League\Container\ServiceProvider\AbstractServiceProvider;
use DT\Home\Psr\Container\ContainerExceptionInterface;
use function DT\Home\plugin_path;

class ConfigServiceProvider extends AbstractServiceProvider {
    /**
     * Provide the services that this provider is responsible for.
     *
     * @param string $id The ID to check.
     * @return bool Returns true if the given ID is provided, false otherwise.
     */
    public function provides( string $id ): bool
    {
        return in_array( $id, [
            ConfigInterface::class,
        ]);
    }

    /**
     * Register the configuration service.
     * @see https://config.thephpleague.com/
     * @throws NotFoundException|ContainerExceptionInterface
     */
    public function register(): void
    {
        $this->getContainer()->addShared(ConfigInterface::class, function () {
            return new Config();
        });

        $config = $this->getContainer()->get( ConfigInterface::class );
        foreach ( glob( plugin_path( 'config/*.php' ) ) as $filename )
        {
            require_once $filename;
        }
    }
}
