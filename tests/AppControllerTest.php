<?php

namespace Tests;

use DT\Home\CodeZone\WPSupport\Router\ServerRequestFactory;
use DT\Home\Controllers\MagicLink\AppController;
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
        $app = app_factory([
            'creation_type' => 'custom',
        ]);
        $apps = container()->get( Apps::class );
        $data = $apps->from( 'settings' );
        $data[] = $app;
        set_plugin_option( 'apps', $data );
        $controller = container()->get( AppController::class );
        $response = $controller->show( $request, [ 'slug' => $app['slug'] ] );
        $this->assertEquals( 200, $response->getStatusCode() );
    }

    /**
     * @test
     */
    public function it_can_hide_apps() {
        $data = app_factory();
        $request = ServerRequestFactory::request( 'POST', 'apps/launcher/key/update-hide-apps', $data );
        $key = $this->faker->md5;
        $controller = container()->get( AppController::class );
        $response = $controller->hide( $request, [ 'key' => $key ] );
        $this->assertEquals( 200, $response->getStatusCode() );
    }

    /**
     * @test
     */
    public function it_can_unhide_apps() {
        $data = app_factory();
        $request = ServerRequestFactory::request( 'POST', 'apps/launcher/key/un-hide-app', $data );
        $key = $this->faker->md5;
        $controller = container()->get( AppController::class );
        $response = $controller->unhide( $request, [ 'key' => $key ] );
        $this->assertEquals( 200, $response->getStatusCode() );
    }

    /**
     * @test
     */
    public function it_can_update_app_order()
    {
        $data = [
            app_factory(),
            app_factory(),
            app_factory(),
        ];
        $request = ServerRequestFactory::request( 'POST', 'apps/launcher/key/update-app-order', $data );
        $key = $this->faker->md5;
        $controller = container()->get( AppController::class );
        $response = $controller->reorder( $request, [ 'key' => $key ] );
        $this->assertEquals( 200, $response->getStatusCode() );
    }
}
