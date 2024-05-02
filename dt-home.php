<?php
/**
 * Plugin Name: DT Home
 * Plugin URI: https://github.com/TheCodeZone/dt_home
 * Description: An app home screen for disciple.tools. Part of the DT Toolbox.
 * Text Domain: dt_home
 * Domain Path: /languages
 * Version:  1.0.0-beta1.3
 * Author URI: https://github.com/TheCodeZone
 * GitHub Plugin URI: https://github.com/TheCodeZone/dt_home
 * Requires at least: 4.7.0
 * (Requires 4.7+ because of the integration of the REST API at 4.7 and the security requirements of this milestone version.)
 * Tested up to: 5.6
 *
 * @package Disciple_Tools
 * @link    https://github.com/thecodezone
 * @license GPL-2.0 or later
 *          https://www.gnu.org/licenses/gpl-2.0.html
 */

use DT\Home\Illuminate\Container\Container;
use DT\Home\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

require_once plugin_dir_path( __FILE__ ) . 'vendor-scoped/scoper-autoload.php';
require_once plugin_dir_path( __FILE__ ) . 'vendor-scoped/autoload.php';
require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

$container = new Container();
$container->singleton( Container::class, function ( $container ) {
	return $container;
} );
$container->singleton( Plugin::class, function ( $container ) {
	return new Plugin( $container );
} );

$plugin_instance = $container->make( Plugin::class );
$plugin_instance->init();
