<?php

namespace DT\Home\Controllers\MagicLink;

use DT\Home\GuzzleHttp\Psr7\ServerRequest as Request;
use DT\Home\Services\Apps;
use function DT\Home\template;

/**
 * Class JsonController
 *
 * Controls the display of application json setting structures.
 */
class AppJsonController {
    private $apps_service;

    public function __construct( Apps $apps ) {
        $this->apps_service = $apps;
    }

    /**
     * Display the app json settings
     *
     * @param Request $request The request object.
     * @param array $params The parameters.
     */
    public function index( Request $request, $params ) {
        $required_properties = [ 'slug', 'name', 'icon', 'type', 'url' ];

        // Fetch apps with json exportable flag enabled.
        $exportable_apps = array_filter( $this->apps_service->for(), function ( $app ) use ( $required_properties ) {

            // Ensure exportable flag is set.
            if ( !isset( $app['is_exportable'] ) || boolval( $app['is_exportable'] ) === false ) {
                return false;
            }

            // Ensure required properties are present.
            return count( $required_properties ) === count( array_intersect( $required_properties, array_keys( $app ) ) );
        } );

        // Sort exportable apps into ascending order by name.
        usort( $exportable_apps, function ( $a, $b ) {
            if ( !isset( $a['name'], $b['name'] ) ) {
                return false;
            }

            return strcmp( $a['name'], $b['name'] );
        } );

        // Next, reshape identified exportable apps into required json output shape.
        $apps = [];
        foreach ( $exportable_apps as $app ) {
            $reshaped_app = [];
            foreach ( $required_properties as $key ) {
                $reshaped_app[$key] = $app[$key];
            }

            $apps[] = $reshaped_app;
        }

        // Call json index template view, with reshaped apps array.
        return template(
            'json/index',
            compact( 'apps' )
        );
    }
}
