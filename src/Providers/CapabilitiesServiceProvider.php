<?php

namespace DT\Home\Providers;

use DT\Home\Conditions\Plugin as IsPlugin;

class CapabilitiesServiceProvider extends ServiceProvider {
	public $route_capabilities = [
      "access_contacts",
      "view_any_contacts"
	];

	public function register(): void {
		add_filter( 'user_has_cap', [ $this, 'user_has_cap' ], 10, 3 );
	}

	/**
	 * Boots the software component.
	 *
	 * This method is responsible for initializing and starting the software component. It does not take any arguments
	 * and does not return any value.
	 *
	 * The method is typically called once during the software's lifecycle, either when the software starts up or when
	 * the component is dynamically instantiated.
	 *
	 * Example usage:
	 * ```
	 * $component = new Component();
	 * $component->boot();
	 * ```
	 */
	public function boot(): void {
	}


	/**
	 * Check if a user has a specific capability.
	 *
	 * This method is used to determine if a user has a specific capability. It takes an array of
	 * all capabilities currently assigned to the user, the capability to check, and any additional
	 * arguments that may be required for the capability check.
	 *
	 * @param array $all_caps An array of all capabilities assigned to the user.
	 * @param string $cap The capability to check.
	 * @param mixed $args Additional arguments required for the capability check.
	 *
	 * @return array An updated array of all capabilities assigned to the user, including any additional capabilities.
	 */
	public function user_has_cap( $all_caps, $cap, $args ) {

		// Add some capabilities to the user if this is a plugin route.
		if ( $this->container->make( IsPlugin::class )->test() ) {
			foreach ( $this->route_capabilities as $cap ) {
				$all_caps[ $cap ] = true;
			}
		}

		return $all_caps;
	}
}
