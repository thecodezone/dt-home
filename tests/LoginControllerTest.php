<?php

namespace Tests;

use DT\Home\CodeZone\WPSupport\Router\ServerRequestFactory;
use DT\Home\Controllers\LoginController;
use DT\Home\Controllers\RedirectController;
use function DT\Home\container;
use function DT\Home\magic_url;

class LoginControllerTest extends TestCase
{
    /**
     * @test
     */
    public function it_renders() {
        $request = ServerRequestFactory::from_globals();
        $controller = container()->get( LoginController::class );
        $response = $controller->show( $request );
        $this->assertEquals( 200, $response->getStatusCode() );
    }

    /**
     * @test
     */
    public function it_fails() {
        $credentials = wp_credentials_factory();
        $request = ServerRequestFactory::request( 'POST', 'apps/login', $credentials );
        $controller = container()->get( LoginController::class );
        $response = $controller->process( $request );
        $this->assertEquals( 200, $response->getStatusCode() );
    }

    /**
     * @test
     */
    public function it_succeeds()
    {
        $credentials = wp_credentials_factory();
        wp_create_user( $credentials['username'], $credentials['password'], $credentials['email'] );
        $request = ServerRequestFactory::request('POST', 'apps/login', $credentials);
        $controller = container()->get(LoginController::class);
        $response = $controller->process($request);
        $this->assertEquals(302, $response->getStatusCode());
    }
}
