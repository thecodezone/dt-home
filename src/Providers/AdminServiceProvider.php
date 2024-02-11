<?php

namespace DT\Launcher\Providers;

use DT\Launcher\CodeZone\Router\Middleware\Stack;
use function DT\Launcher\namespace_string;

class AdminServiceProvider extends ServiceProvider {
	/**
	 * Do any setup needed before the theme is ready.
	 * DT is not yet registered.
	 */
	public function register(): void {
		add_action( 'admin_menu', [ $this, 'register_menu' ], 99 );
	}

	/**
	 * Register the admin menu
	 *
	 * @return void
	 */
	public function register_menu(): void {
		add_submenu_page( 'dt_extensions',
			__( 'DT App Launcher', 'dt_launcher' ),
			__( 'DT App Launcher', 'dt_launcher' ),
			'manage_dt',
			'dt_launcher',
			[ $this, 'register_router' ]
		);
	}

	/**
	 * Register the admin router using the middleware stack via filter.
	 *
	 * @return void
	 */
	public function register_router(): void {
		apply_filters( namespace_string( 'middleware' ), $this->container->make( Stack::class ) )
			->run();
	}

	/**
	 * Boot the plugin
	 *
	 * This method checks if the current context is the admin area and then
	 * registers the required plugins using TGMPA library.
	 *
	 * @return void
	 */
	public function boot(): void {
		/*
		 * Array of plugin arrays. Required keys are name and slug.
		 * If the source is NOT from the .org repo, then source is also required.
		 */
		$plugins = [
			[
				'name'     => 'Disciple.Tools Dashboard',
				'slug'     => 'disciple-tools-dashboard',
				'source'   => 'https://github.com/DiscipleTools/disciple-tools-dashboard/releases/latest/download/disciple-tools-dashboard.zip',
				'required' => false,
			],
			[
				'name'     => 'Disciple.Tools Genmapper',
				'slug'     => 'disciple-tools-genmapper',
				'source'   => 'https://github.com/DiscipleTools/disciple-tools-genmapper/releases/latest/download/disciple-tools-genmapper.zip',
				'required' => true,
			],
			[
				'name'     => 'Disciple.Tools Autolink',
				'slug'     => 'disciple-tools-autolink',
				'source'   => 'https://github.com/DiscipleTools/disciple-tools-genmapper/releases/latest/download/disciple-tools-autolink.zip',
				'required' => true,
			],
		];

		/*
		 * Array of configuration settings. Amend each line as needed.
		 *
		 * Only uncomment the strings in the config array if you want to customize the strings.
		 */
		$config = [
			'id'           => 'disciple_tools',
			// Unique ID for hashing notices for multiple instances of TGMPA.
			'default_path' => '/partials/plugins/',
			// Default absolute path to bundled plugins.
			'menu'         => 'tgmpa-install-plugins',
			// Menu slug.
			'parent_slug'  => 'plugins.php',
			// Parent menu slug.
			'capability'   => 'manage_options',
			// Capability needed to view plugin install page, should be a capability associated with the parent menu used.
			'has_notices'  => true,
			// Show admin notices or not.
			'dismissable'  => true,
			// If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => 'These are recommended plugins to complement your Disciple.Tools system.',
			// If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => true,
			// Automatically activate plugins after installation or not.
			'message'      => '',
			// Message to output right before the plugins table.
		];

		tgmpa( $plugins, $config );
	}
}
