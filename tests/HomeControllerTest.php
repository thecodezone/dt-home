<?php

namespace Tests;

use DT\Home\CodeZone\WPSupport\Router\ServerRequestFactory;
use DT\Home\Controllers\MagicLink\HomeController;
use function DT\Home\container;

class HomeControllerTest extends TestCase
{
    /**
     * @test
     */
    public function it_shows() {
        $request = ServerRequestFactory::from_globals();
        $key = $this->faker->md5;
        $controller = container()->get( HomeController::class );
        $response = $controller->show( $request, [ 'key' => $key ] );
        $this->assertEquals( 200, $response->getStatusCode() );
    }

    /**
     * @test
     */
    public function it_can_hide_apps() {
        $data = app_factory();
        $request = ServerRequestFactory::request( 'POST', 'apps/launcher/key/update-hide-apps', $data );
        $key = $this->faker->md5;
        $controller = container()->get( HomeController::class );
        $response = $controller->update_hide_app( $request, [ 'key' => $key ] );
        $this->assertEquals( 200, $response->getStatusCode() );
    }

    /**
     * @test
     */
    public function it_can_unhide_apps() {
        $data = app_factory();
        $request = ServerRequestFactory::request( 'POST', 'apps/launcher/key/un-hide-app', $data );
        $key = $this->faker->md5;
        $controller = container()->get( HomeController::class );
        $response = $controller->update_unhide_app( $request, [ 'key' => $key ] );
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
        $controller = container()->get( HomeController::class );
        $response = $controller->update_app_order( $request, [ 'key' => $key ] );
        $this->assertEquals( 200, $response->getStatusCode() );
    }
}
