<?php

namespace Tests;

use DT\Home\CodeZone\WPSupport\Router\ServerRequestFactory;
use DT\Home\Controllers\MagicLink\LauncherController;
use function DT\Home\container;

class LauncherControllerTest extends TestCase
{
    /**
     * @test
     */
    public function it_shows() {
        $request = ServerRequestFactory::from_globals();
        $key = $this->faker->md5;
        $controller = container()->get( LauncherController::class );
        $response = $controller->show( $request, [ 'key' => $key ] );
        $this->assertEquals( 200, $response->getStatusCode() );
    }
}
