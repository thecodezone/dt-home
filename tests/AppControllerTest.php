<?php

namespace Tests;

use DT\Home\CodeZone\WPSupport\Router\ServerRequestFactory;
use DT\Home\Controllers\AppController;
use DT\Home\Services\Apps;
use function DT\Home\container;
use function DT\Home\set_plugin_option;

class AppControllerTest extends TestCase
{
    /**
     * @test
     */
    public function it_handles_missing_apps() {
        $request = ServerRequestFactory::from_globals();
        $app = app_factory();
        $controller = container()->get( AppController::class );
        $response = $controller->show( $request, [ 'slug' => $app['slug'] ] );
        $this->assertEquals( 404, $response->getStatusCode() );
    }

    /**
     * @test
     */
    public function it_renders() {
        $request = ServerRequestFactory::from_globals();
        $app = app_factory();
        $apps = container()->get( Apps::class );
        $data = $apps->all();
        $data[] = $app;
        set_plugin_option( 'apps', $data );
        $controller = container()->get( AppController::class );
        $response = $controller->show( $request, [ 'slug' => $app['slug'] ] );
        $this->assertEquals( 200, $response->getStatusCode() );
    }
}
