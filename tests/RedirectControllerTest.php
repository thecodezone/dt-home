<?php

namespace Tests;

use DT\Home\CodeZone\WPSupport\Router\ServerRequestFactory;
use DT\Home\Controllers\RedirectController;
use function DT\Home\container;
use function DT\Home\magic_url;

class RedirectControllerTest extends TestCase
{
    /**
     * @test
     */
    public function it_redirects_if_not_logged_in() {
        $request = ServerRequestFactory::from_globals();
        $controller = container()->get( RedirectController::class );
        $response = $controller->show( $request );
        $this->assertEquals( 302, $response->getStatusCode() );
    }

    /**
     * @test
     */
    public function it_redirects_to_magic_link_if_not_activated()
    {
        // Create a partial mock of RedirectController
        $redirect_controller = $this->getMockBuilder( RedirectController::class )
            ->onlyMethods( [ 'is_activated' ] )
            ->getMock();

        // Configure the mock to return false upon calling is_activated()
        $redirect_controller->expects( $this->once() )
            ->method( 'is_activated' )
            ->willReturn( false );

        $this->as_user();
        $request = ServerRequestFactory::from_globals();
        $response = $redirect_controller->show( $request );

        $this->assertEquals( 302, $response->getStatusCode() );
        $this->assertEquals( magic_url(), $response->getHeaderLine( 'Location' ) );
    }
}
