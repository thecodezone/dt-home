<?php

namespace Tests;

use DT\Home\CodeZone\WPSupport\Router\ServerRequestFactory;
use DT\Home\Controllers\MagicLink\ShareController;

class ShareControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function it_shows() {
        $this->as_user();
        $request = ServerRequestFactory::from_globals();
        $share_controller = $this->createPartialMock( ShareController::class, [ 'set_cookie' ] );
        $share_controller->expects( $this->once() )
            ->method( 'set_cookie' );
        $response = $share_controller->show( $request );
        $this->assertEquals( 302, $response->getStatusCode() );
    }
}
