<?php

namespace Tests;

use DT\Home\CodeZone\WPSupport\Router\ServerRequestFactory;
use DT\Home\Controllers\MagicLink\TrainingController;
use function DT\Home\container;

class TrainingControllerTest extends TestCase
{
    /**
     * @test
     */
    public function it_shows() {
        $request = ServerRequestFactory::from_globals();
        $controller = container()->get( TrainingController::class );
        $response = $controller->show( $request );
        $this->assertEquals( 200, $response->getStatusCode() );
    }
}
