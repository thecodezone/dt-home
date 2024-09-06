<?php

namespace Tests;

use DT\Home\CodeZone\WPSupport\Router\ServerRequestFactory;
use DT\Home\Controllers\LoginController;
use DT\Home\Controllers\RedirectController;
use DT\Home\Controllers\RegisterController;
use function DT\Home\container;
use function DT\Home\magic_url;

class RegisterControllerTest extends TestCase
{
    /**
     * @test
     */
    public function it_renders() {
        $request = ServerRequestFactory::from_globals();
        $controller = container()->get( RegisterController::class );
        $response = $controller->show( $request );
        $this->assertEquals( 200, $response->getStatusCode() );
    }

    /**
     * @test
     */
    public function it_processes()
    {
        $data = registration_factory();
        $request = ServerRequestFactory::request( 'POST', 'apps/register', $data );
        $controller = container()->get( RegisterController::class );
        $response = $controller->process( $request );
        $this->assertEquals( 302, $response->getStatusCode() );
    }

    /**
     * @test
     */
    public function it_requires_fields()
    {
        $data = registration_factory([
            'email' => '',
        ]);
        $request = ServerRequestFactory::request( 'POST', 'apps/register', $data );
        $controller = container()->get( RegisterController::class );
        $response = $controller->process( $request );
        $this->assertEquals( 200, $response->getStatusCode() );
    }

    /**
     * @test
     */
    public function it_requires_matching_passwords()
    {
        $data = registration_factory([
            'password' => 'wing',
            'confirm_password' => 'ding',
        ]);
        $request = ServerRequestFactory::request( 'POST', 'apps/register', $data );
        $controller = container()->get( RegisterController::class );
        $response = $controller->process( $request );
        $this->assertEquals( 200, $response->getStatusCode() );
    }
}
