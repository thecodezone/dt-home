<?php

namespace DT\Home\Providers;

use DT\Home\League\Container\Exception\NotFoundException;
use DT\Home\League\Container\ServiceProvider\AbstractServiceProvider;
use DT\Home\League\Container\ServiceProvider\BootableServiceProviderInterface;
use DT\Home\Psr\Container\ContainerExceptionInterface;
use DT\Home\Services\Settings;
use function DT\Home\config;

/**
 * Class AdminServiceProvider
 *
 * This class is responsible for providing admin services and loading necessary plugins using the TGM Plugin Activation library.
 */
class AdminServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface {
    /**
     * Provide the services that this provider is responsible for.
     *
     * @param string $id The ID to check.
     * @return bool Returns true if the given ID is provided, false otherwise.
     */
    public function provides( string $id ): bool
    {
        return in_array( $id, [
            Settings::class
        ] );
    }

    /**
     * Eager load the admin service
     *
     * @return void
     * @throws NotFoundException|ContainerExceptionInterface
     */
    public function boot(): void
    {
        add_action( 'wp_loaded', [ $this, 'wp_loaded' ] );

        $this->getContainer()->addShared( Settings::class, function () {
            return new Settings();
        } );
        $this->getContainer()->get( Settings::class );
    }

    /**
     * Register any services provided.
     *
     * This method is responsible for registering any services. It will be called
     * when the service is requested from the container.
     */
    public function register(): void
    {
        // The settings service is loaded eagerly in the boot method.
    }

    /**
     * Loads the necessary plugins using the TGM Plugin Activation library.
     *
     * This function should be called on the `wp_loaded` action hook
     * to ensure that all required plugins are properly loaded.
     *
     * @return void
     */
    public function wp_loaded(): void
    {
        tgmpa( config()->get( 'services.tgmpa.plugins' ), config()->get( 'services.tgmpa.config' ) );
    }
}
