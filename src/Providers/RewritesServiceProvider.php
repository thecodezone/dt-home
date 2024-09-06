<?php

namespace DT\Home\Providers;

use DT\Home\CodeZone\WPSupport\Config\ConfigInterface;
use DT\Home\League\Container\ServiceProvider\AbstractServiceProvider;
use DT\Home\CodeZone\WPSupport\Rewrites\Rewrites;
use DT\Home\CodeZone\WPSupport\Rewrites\RewritesInterface;

/**
 * Class RewritesServiceProvider
 *
 * This class is responsible for providing the necessary services for rewriting routes.
 * It extends the AbstractServiceProvider class.
 */
class RewritesServiceProvider extends AbstractServiceProvider {
    protected $config;

    /**
     * __construct method initializes the object with the provided Configuration object.
     *
     * @param ConfigInterface $config The Configuration object to initialize the object with.
     *
     * @return void
     */
    public function __construct( ConfigInterface $config )
    {
        $this->config = $config;
    }

    /**
     * Provide the services that this provider is responsible for.
     *
     * @param string $id The ID to check.
     * @return bool Returns true if the given ID is provided, false otherwise.
     */
    public function provides( string $id ): bool
    {
        return in_array($id, [
            RewritesInterface::class
        ]);
    }

    /**
     * Retrieves the array of rewrite rules from the configuration.
     *
     * @return array The array of rewrite rules configured in the application.
     */
    public function rewrites(): array {
        return $this->config->get( 'routes.rewrites' );
    }

    /**
     * Registers the Rewrites class in the container as a shared service.
     *
     * This method adds the Rewrites class to the container as a shared service.
     * It uses a closure to instantiate the class, and passes the array of rewrite rules
     * obtained from the rewrites() method as a parameter to the class constructor.
     *
     * @return void
     */
    public function register(): void
    {
        $this->getContainer()->addShared(RewritesInterface::class, function () {
            return new Rewrites(
                $this->rewrites()
            );
        });
    }
}
