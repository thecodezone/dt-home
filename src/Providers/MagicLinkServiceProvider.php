<?php

namespace DT\Home\Providers;

use DT\Home\League\Container\ServiceProvider\AbstractServiceProvider;
use DT\Home\League\Container\ServiceProvider\BootableServiceProviderInterface;
use DT\Home\Psr\Container\ContainerExceptionInterface;
use DT\Home\Psr\Container\NotFoundExceptionInterface;
use function DT\Home\config;
use function DT\Home\namespace_string;

/**
 * MagicLinkServiceProvider class
 *
 * This class is responsible for providing and registering magic link services.
 */
class MagicLinkServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface {
    protected $container;

    /**
     * Provide the services that this provider is responsible for.
     *
     * @param string $id The ID to check.
     * @return bool Returns true if the given ID is provided, false otherwise.
     */
    public function provides( string $id ): bool
    {
        return \in_array($id, [
            namespace_string( 'magic_links' ),
            ...config( 'magic.links' )
        ], \true);
    }


    /**
     * Register the magic links array and the magic link classes.
     *
     * When WordPress is loaded, init the magic links.
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function boot(): void
    {

        add_action( 'wp_loaded', [ $this, 'wp_loaded' ], 20 );


        $this->getContainer()->add( namespace_string( 'magic_links' ), function () {
            return apply_filters( namespace_string( 'magic_links' ), config( 'magic.links' ) );
        } );

        foreach ( $this->getContainer()->get( namespace_string( 'magic_links' ) ) as $magic_link ) {
            $this->getContainer()->addShared( $magic_link, function () use ( $magic_link ) {
                return new $magic_link();
            } );
        }
    }

    /**
     * Register any services provided.
     *
     * This method is responsible for registering any services. It will be called
     * when the service is requested from the container.
     */
    public function register(): void {
        // The magic links are eager-loaded in the boot method
    }

    /**
     * Initialize the magic links
     */
    public function wp_loaded() {
        $magic_links = $this->container->get( namespace_string( 'magic_links' ) );

        foreach ( $magic_links as $magic_link ) {
            $this->container->get( $magic_link );
        }
    }
}
