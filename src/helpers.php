<?php

namespace DT\Home;

use DT\Home\Illuminate\Http\RedirectResponse;
use DT\Home\Illuminate\Http\Request;
use DT\Home\Illuminate\Support\Str;
use DT\Home\League\Plates\Engine;
use DT\Home\Services\Template;
use DT_Magic_URL;

/**
 * Retrieves the instance of the Plugin class.
 *
 * @return Plugin The instance of the Plugin class.
 */
function plugin(): Plugin
{
    return Plugin::$instance;
}

/**
 * Returns the container object.
 *
 * @return Illuminate\Container\Container The container object.
 */
function container(): Illuminate\Container\Container
{
    return plugin()->container;
}

/**
 * Checks if the route rewrite rule exists in the WordPress rewrite rules.
 *
 * @return bool Whether the route rewrite rule exists in the rewrite rules.
 * @global WP_Rewrite $wp_rewrite The main WordPress rewrite rules object.
 *
 */
function has_route_rewrite(): bool
{
    global $wp_rewrite;

    if ( !is_array( $wp_rewrite->rules ) ) {
        return false;
    }

    return array_key_exists( '^dt-home/(.+)/?', $wp_rewrite->rules );
}

/**
 * Returns the path of a plugin file or directory, relative to the plugin directory.
 *
 * @param string $path The path of the file or directory relative to the plugin directory. Defaults to an empty string.
 *
 * @return string The full path of the file or directory, relative to the plugin directory.
 */
function plugin_path( string $path = '' ): string
{
    return '/' . implode('/', [
            trim( Str::remove( '/src', plugin_dir_path( __FILE__ ) ), '/' ),
            trim( $path, '/' ),
    ]);
}

/**
 * Get the source path using the given path.
 *
 * @param string $path The path to append to the source directory.
 *
 * @return string The complete source path.
 */
function src_path( string $path = '' ): string
{
    return plugin_path( 'src/' . $path );
}

/**
 * Returns the path to the resources directory.
 *
 * @param string $path Optional. Subdirectory path to append to the resources directory.
 *
 * @return string The path to the resources directory, with optional subdirectory appended.
 */
function resources_path( string $path = '' ): string
{
    return plugin_path( 'resources/' . $path );
}

/**
 * Returns the path to the routes directory.
 *
 * @param string $path Optional. Subdirectory path to append to the routes directory.
 *
 * @return string The path to the routes directory, with optional subdirectory appended.
 */
function routes_path( string $path = '' ): string
{
    return plugin_path( 'routes/' . $path );
}

/**
 * Returns the path to the views directory.
 *
 * @param string $path Optional. Subdirectory path to append to the views directory.
 *
 * @return string The path to the views directory, with optional subdirectory appended.
 */
function views_path( string $path = '' ): string
{
    return plugin_path( 'resources/views/' . $path );
}

/**
 * Generates the URL for a plugin file.
 *
 * @param string $path Optional. The path of the file within the plugin directory.
 *                     If not specified, returns the URL of the plugin directory itself.
 *
 * @return string The URL for the specified plugin file.
 */
function plugin_url( string $path = '' ): string
{
    return trim( Str::remove( '/src', plugin_dir_url( __FILE__ ) ), '/' ) . '/' . ltrim( $path, '/' );
}

/**
 * Generate the URL for a specific route.
 *
 * @param string $path Optional. The path to append to the home route. Defaults to an empty string.
 *
 * @return string The generated URL for the specified route.
 */
function route_url( string $path = '' ): string
{
    if ( !has_route_rewrite() ) {
        return site_url() . '?' . http_build_query( [ Plugin::ROUTE_QUERY_PARAM => $path ] );
    } else {
        return site_url( Plugin::HOME_ROUTE . '/' . ltrim( $path, '/' ) );
    }
}

/**
 * Renders a view using the provided view engine.
 *
 * @param string $view Optional. The name of the view to render. Defaults to an empty string.
 * @param array $args Optional. An array of data to pass to the view. Defaults to an empty array.
 *
 * @return string|Engine The rendered view if a view name is provided, otherwise the view engine object.
 */
function view( string $view = "", array $args = [] ): string|Engine
{
    $engine = container()->make( Engine::class );
    if ( !$view ) {
        return $engine;
    }

    return $engine->render( $view, $args );
}

/**
 * Renders a template using the Template service.
 *
 * @param string $template Optional. The template to render. If not specified, the Template service instance is returned.
 * @param array $args Optional. An array of arguments to be passed to the template.
 *
 * @return mixed If $template is not specified, an instance of the Template service is returned.
 *               If $template is specified, the rendered template is returned.
 */
function template( string $template = "", array $args = [] ): mixed
{
    $service = container()->make( Template::class );
    if ( !$template ) {
        return $service;
    }

    return $service->render( $template, $args );
}

/**
 * Returns the Request object.
 *
 * @return Request The Request object.
 */
function request(): Request
{
    return container()->make( Request::class );
}

/**
 * Creates a new RedirectResponse instance for the given URL.
 *
 * @param string $url The URL to redirect to.
 * @param int $status Optional. The status code for the redirect response. Default is 302.
 *
 * @return RedirectResponse A new RedirectResponse instance.
 */
function redirect( string $url, int $status = 302 ): RedirectResponse
{
    return container()->makeWith(RedirectResponse::class, [
        'url' => $url,
        'status' => $status,
    ]);
}


/**
 * Returns the registered magic apps for a specific root and type.
 *
 * @param string $root The root of the magic apps.
 * @param string $type The type of the magic app.
 *
 * @return array|bool The registered magic apps for the given root and type.
 *                  Returns an array if found, otherwise returns false.
 */
function magic_app( $root, $type ): array|bool
{
    $magic_apps = apply_filters( 'dt_magic_url_register_types', [] );
    $root_apps = $magic_apps[$root] ?? [];

    return $root_apps[$type] ?? false;
}

/**
 * Generates a magic URL for a given root, type, and ID.
 *
 * @param string $root The root of the magic URL.
 * @param string $type The type of the magic URL.
 * @param int $id The ID of the post to generate the magic URL for.
 *
 * @return string The generated magic URL.
 */
function get_magic_url( $root, $type, $id ): string
{
    $app = magic_app( $root, $type );
    if ( !$app ) {
        return "";
    }
    if ( $app['post_type'] === 'user' ) {

        $app_user_key = get_user_option( $app['meta_key'] );
        $app_url_base = trailingslashit( trailingslashit( site_url() ) . $app['url_base'] );
        return $app_url_base . $app_user_key;
    } else {
        return DT_Magic_URL::get_link_url_for_post(
            $app["post_type"],
            $id,
            $app["root"],
            $app["type"]
        );
    }
}

/**
 * Generates a magic URL for a given root, type, and ID.
 *
 * @param string $root The root of the magic URL.
 * @param string $type The type of the magic URL.
 * @param int $id The ID of the post to generate the magic URL for.
 *
 * @return string The generated magic URL.
 */
function magic_url( $action = "", $key = "" ): string
{
    if ( !$key ) {
        if ( !get_current_user_id() ) {
            return route_url( 'login' );
        }

        $url = get_magic_url( "dt-home", "launcher", get_current_user_id() );

        if ( $action ) {
            return $url . '/' . $action;
        }

        return $url;
    }

    return DT_Magic_URL::get_link_url( 'dt-home', 'launcher', $key, $action );
}

/**
 * Generates a fully qualified class name by appending the given string to the Plugin class namespace.
 *
 * @param string $string The string to append to the Plugin class namespace.
 *
 * @return string The fully qualified class name.
 */
function namespace_string( string $string )
{
    return Plugin::class . '\\' . $string;
}

/**
 * Converts line breaks to HTML line breaks in a given string.
 *
 * @param string $string The string in which line breaks need to be converted.
 *
 * @return string The string with line breaks converted to HTML line breaks.
 */
function breaks_to_html( string $string )
{
    return str_replace( "\n", '<br>', $string );
}

/**
 * Converts HTML line breaks to line breaks in a given string.
 *
 * @param string $string The string in which HTML line breaks need to be converted.
 *
 * @return string The string with HTML line breaks converted to line breaks.
 */
function is_plugin_active( $plugin )
{
    return in_array( $plugin, (array) get_option( 'active_plugins', [] ), true ) || is_plugin_active_for_network( $plugin );
}

/**
 * Checks if a plugin is active for the entire network.
 *
 * @param string $plugin The plugin file path relative to the plugins directory.
 *
 * @return bool Whether the plugin is active for the entire network.
 */
function is_plugin_active_for_network( $plugin )
{
    if ( !is_multisite() ) {
        return false;
    }

    $plugins = get_site_option( 'active_sitewide_plugins' );
    if ( isset( $plugins[$plugin] ) ) {
        return true;
    }

    return false;
}
