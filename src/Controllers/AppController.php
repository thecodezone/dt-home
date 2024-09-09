<?php

namespace DT\Home\Controllers;

use DT\Home\Illuminate\Http\Request;
use DT\Home\Illuminate\Http\Response;
use DT\Home\Services\Apps;
use function DT\Home\collect;
use function DT\Home\namespace_string;
use function DT\Home\template;

/**
 * Class AppController
 *
 * Controls the display of application details.
 */
class AppController
{
    /**
     * Displays the application in an iframe webview
     *
     * This method retrieves the application with the provided ID from the "dt_home_apps" option and displays its details.
     * If the application is not found, a 404 error response is returned.
     *
     * @param Request $request The request object.
     * @param Response $response The response object.
     * @param int $slug The ID of the application to display.
     *
     * @return Response The response object containing the rendered application details.
     */
    public function show(Request $request, Response $response, Apps $apps, $key, $slug)
    {
        // Fetch the app
        $app = collect($apps->all())->where('slug', $slug)->first();

        if (!$app) {
            return $response->setStatusCode(404)->setContent('Not Found');
        }

        // Check if there is a custom action to render the app
        $action = has_action('dt_home_app_render');
        if ($action) {
            add_action(namespace_string('filter_asset_queue'), function ($queue) use ($app) {
                // Don't filter assets
            });
            do_action('dt_home_app_render', $app);
            exit;
        }

        // Check if the app has a custom template
        $html = apply_filters('dt_home_app_template', "", $app);

        if ($html) {
            return $response->setContent($html);
        }

        // Check to see if the app has an iframe URL
        $url = apply_filters('dt_home_webview_url', $app['url'] ?? '', $app);
                $url = $this->addOrUpdateQueryParam($url, 'dt_home', 'true');

        if (!$url) {
            // No URL found 404
            return $response->setStatusCode(404)->setContent('Not Found');
        }

        return $response->setContent(
            template('web-view', compact('app', 'url'))
        );
    }

    /**
     * Adds or updates a query parameter in a URL.
     *
     * @param string $url The original URL.
     * @param string $key The query parameter key.
     * @param string $value The query parameter value.
     *
     * @return string The updated URL.
     */
    private function addOrUpdateQueryParam($url, $key, $value)
    {
        // Split the URL into the base and the query string
        $url_parts = explode('?', $url, 2);
        $base_url = $url_parts[0];
        $query_string = $url_parts[1] ?? '';

        // Parse the query string into an associative array
        parse_str($query_string, $query_params);

        // Update the query parameters
        $query_params[$key] = $value;

        // Rebuild the query string
        $new_query_string = http_build_query($query_params);
dd($base_url . '?' . $new_query_string);
        return $base_url . '?' . $new_query_string;
    }
}
