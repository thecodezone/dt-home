<?php

namespace DT\Home\Providers;

use DT\Home\PostTypes\StarterPostType;

class PostTypeServiceProvider extends ServiceProvider {

	/**
	 * Do any setup needed before the theme is ready.
	 * DT is not yet registered.
	 */
	public function register(): void {
	}

	/**
	 * Do any setup needed after the theme is ready.
	 * DT is registered.
	 *
	 * @return void
	 */
	public function boot(): void {
		add_filter( 'dt_post_type_modules', [ $this, 'dt_post_type_modules' ], 1, 1 );
		$this->container->make( StarterPostType::class );
	}

	/**
	 * Retrieves an array of post type modules.
	 *
	 * Each module is represented by an associative array with the following keys:
	 *   - 'name': The name of the module.
	 *   - 'enabled': A boolean value indicating whether the module is enabled or not.
	 *   - 'locked': A boolean value indicating whether the module is locked or not.
	 *   - 'prerequisites': An array of module names that this module depends on.
	 *   - 'post_type': The post type associated with the module.
	 *   - 'description': The description of the module.
	 *
	 * @return array An array of post type modules.
	 */
	public function dt_post_type_modules(): array {
		$modules['starter_base'] = [
			'name'          => __( 'Starter', 'dt_home' ),
			'enabled'       => true,
			'locked'        => true,
			'prerequisites' => [ 'contacts_base' ],
			'post_type'     => 'starter_post_type',
			'description'   => __( 'Default starter functionality', 'dt_home' )
		];

		return $modules;
	}
}
