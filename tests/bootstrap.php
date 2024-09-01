<?php
/**
 * PHPUnit bootstrap file
 *
 * @package Disciple.Tools
 */
$_tests_dir   = getenv( 'WP_TESTS_DIR' ) ? getenv( 'WP_TESTS_DIR' ) : rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
$_core_dir    = getenv( 'WP_CORE_DIR' ) ? getenv( 'WP_CORE_DIR' ) : rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress';
$_theme_dir   = getenv( 'WP_THEME_DIR' ) ? getenv( 'WP_THEME_DIR' ) : $_core_dir . '/wp-content/themes/disciple-tools-theme';
// @phpcs:ignore
$_plugin_file = $_ENV['WP_PLUGIN_FILE'] ?? false ?: dirname( __DIR__ ) . '/' . basename( dirname( __DIR__ ) ) . '.php';

if ( ! file_exists( $_tests_dir . '/includes/functions.php' ) ) {
    echo "Could not find " . $_tests_dir . "/includes/functions.php, have you run tests/install-wp-tests.sh ?" . PHP_EOL; //@phpcs:ignore
    exit( 1 );
}

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';

/**
 * Registers theme
 */
$_register_theme = function () use ( $_tests_dir, $_core_dir, $_theme_dir, $_plugin_file ) {
    $current_theme = basename( $_theme_dir );
    $theme_root    = dirname( $_theme_dir );
    add_filter( 'theme_root', function () use ( $theme_root ) {
        return $theme_root;
    } );

    register_theme_directory( $theme_root );

    add_filter( 'pre_option_template', function () use ( $current_theme ) {
        return $current_theme;
    } );
    add_filter( 'pre_option_stylesheet', function () use ( $current_theme ) {
        return $current_theme;
    } );
    add_filter( 'init', function () {
        require_once get_template_directory() . '/dt-core/setup-functions.php';
        dt_setup_roles_and_permissions();
    }, 500, 0 );

    require $_plugin_file;
};

tests_add_filter( 'muplugins_loaded', $_register_theme );

// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';
