<?php

namespace DT\Launcher\Providers;

use DT\Launcher\PostTypes\StarterPostType;

class PostTypeServiceProvider extends ServiceProvider {

	/**
	 * Do any setup needed before the theme is ready.
	 * DT is not yet registered.
	 */
	public function register(): void {
	}

	/**
	 * Do any setup after services have been registered and the theme is ready
	 */
	public function boot(): void {
		add_filter( 'dt_post_type_modules', [ $this, 'dt_post_type_modules' ], 1, 1 );
		$this->container->make( StarterPostType::class );
	}

	/**
	 * Register the post type modules
	 * @return array
	 */
	public function dt_post_type_modules(): array {
		$modules['starter_base'] = [
			'name'          => __( 'Starter', 'dt-launcher' ),
			'enabled'       => true,
			'locked'        => true,
			'prerequisites' => [ 'contacts_base' ],
			'post_type'     => 'starter_post_type',
			'description'   => __( 'Default starter functionality', 'dt-launcher' )
		];

		return $modules;
	}
}
