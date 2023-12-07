<?php

namespace DT\Launcher;

use DT\Launcher\Illuminate\Container\Container;
use DT\Launcher\Providers\PluginServiceProvider;

class Plugin {
	const REQUIRED_PHP_VERSION = '1.19';
	public static Plugin $instance;

	public Container $container;
	public PluginServiceProvider $provider;


	public function __construct( Container $container ) {
		$this->container = $container;
		$this->provider  = $container->make( PluginServiceProvider::class );
	}

	public function init() {
		static::$instance = $this;
		$this->provider->register();
		add_action( 'after_setup_theme', [ $this, 'after_setup_theme' ], 20 );
		add_filter( 'dt_launchers', [ $this, 'dt_launchers' ] );
	}

	/**
	 * Runs after_theme_setup
	 * @return void
	 */
	public function after_setup_theme(): void {
		if ( ! $this->is_dt_version() ) {
			add_action( 'admin_notices', [ $this, 'admin_notices' ] );
			add_action( 'wp_ajax_dismissed_notice_handler', [ $this, 'ajax_notice_handler' ] );

			return;
		}

		if ( ! $this->is_dt_theme() ) {
			return;
		}

		if ( ! defined( 'DT_FUNCTIONS_READY' ) ) {
			require_once get_template_directory() . '/dt-core/global-functions.php';
		}

		$this->provider->boot();
	}

	/**
	 * is DT up-to-date?
	 * @return bool
	 */
	public function is_dt_version(): bool {
		if ( ! $this->is_dt_theme() ) {
			return false;
		}
		$wp_theme = wp_get_theme();

		return version_compare( $wp_theme->version, self::REQUIRED_PHP_VERSION, '>=' );
	}

	/**
	 * Is the DT Theme installed?
	 * @return bool
	 */
	protected function is_dt_theme(): bool {
		return class_exists( 'Disciple_Tools' );
	}

	/**
	 * Register the plugin
	 * @return array
	 */
	public function dt_launchers(): array {
		$plugin_data = get_file_data( __FILE__, [
			'Version'     => 'Version',
			'Plugin Name' => 'Plugin Name',
		], false );

		$plugins['dt-launcher'] = [
			'plugin_url' => trailingslashit( plugin_dir_url( __FILE__ ) ),
			'version'    => $plugin_data['Version'] ?? null,
			'name'       => $plugin_data['Plugin Name'] ?? null,
		];

		return $plugins;
	}
}
