<?php

namespace Tests;

use DT\Home\CodeZone\WPSupport\Router\ServerRequestFactory;
use DT\Home\Controllers\Admin\AppSettingsController;
use DT\Home\Services\Apps;
use function DT\Home\container;
use function DT\Home\get_plugin_option;
use function DT\Home\set_plugin_option;

class AppSettingsControllerTest extends TestCase
{
    /**
     * @test
     */
    public function it_renders() {
        $request = ServerRequestFactory::from_globals();
        $controller = container()->get( AppSettingsController::class );
        $response = $controller->show( $request );
        $this->assertEquals( 200, $response->getStatusCode() );
    }

    /**
     * @test
     */
    public function it_renders_available_apps() {
        $request = ServerRequestFactory::from_globals();
        $controller = container()->get( AppSettingsController::class );
        $response = $controller->show_available_apps( $request );
        $this->assertEquals( 200, $response->getStatusCode() );
    }

    /**
     * @test
     */
    public function it_renders_create() {
        $request = ServerRequestFactory::from_globals();
        $controller = container()->get( AppSettingsController::class );
        $response = $controller->create( $request );
        $this->assertEquals( 200, $response->getStatusCode() );
    }


    /**
     * @test
     */
    public function it_creates_apps()
    {
        $app = $this->app_factory();
        set_plugin_option( 'require_login', 'off' );
        $request = ServerRequestFactory::request('POST', '/admin.php?page=dt_home&tab=app&action=create', $app );
        $controller = container()->get( AppSettingsController::class );
        $response = $controller->store( $request );
        $this->assertEquals( 302, $response->getStatusCode() );
        $apps = container()->get( Apps::class )->all();
        $this->assertContains( $app['slug'], array_column( $apps, 'slug' ) );
    }

    /**
     * @test
     */
    public function it_increments_duplicate_app_slugs()
    {
        $app = $this->app_factory();
        set_plugin_option( 'require_login', 'off' );
        $request = ServerRequestFactory::request( 'POST', '/admin.php?page=dt_home&tab=app&action=create', $app );
        $controller = container()->get( AppSettingsController::class );
        $response = $controller->store( $request );
        $response2 = $controller->store( $request );
        $this->assertEquals( 302, $response->getStatusCode() );
        $this->assertEquals( 302, $response2->getStatusCode() );
        $apps = container()->get( Apps::class )->all();
        $this->assertContains( $app['slug'], array_column( $apps, 'slug' ) );
        $this->assertContains( $app['slug'] . '_2', array_column( $apps, 'slug' ) );
    }

    /**
     * @test
     */
    public function it_hides_apps()
    {
        $app = $this->app_factory([
            'is_hidden' => false
        ]);
        set_plugin_option( 'apps', [ $app ] );
        $request = ServerRequestFactory::request( 'GET', '/admin.php?page=dt_home&tab=app&action=hide/' . $app['slug'] );
        $controller = container()->get( AppSettingsController::class );
        $response = $controller->hide( $request, [ 'slug' => $app['slug'] ] );
        $this->assertEquals( 302, $response->getStatusCode() );
        $apps = container()->get( Apps::class )->all();
        $this->assertEquals( 1, $apps[0]['is_hidden'] );
    }

    /**
     * @test
     */
    public function it_unhides_apps()
    {
        $app = $this->app_factory([
            'is_hidden' => true
        ]);
        set_plugin_option( 'apps', [ $app ] );
        $request = ServerRequestFactory::request( 'GET', '/admin.php?page=dt_home&tab=app&action=unhide/' . $app['slug'] );
        $controller = container()->get( AppSettingsController::class );
        $response = $controller->unhide( $request, [ 'slug' => $app['slug'] ] );
        $this->assertEquals( 302, $response->getStatusCode() );
        $apps = container()->get( Apps::class )->all();
        $this->assertEquals( 0, $apps[0]['is_hidden'] );
    }

    /**
     * @test
     */
    public function it_renders_update() {
        $app = $this->app_factory([
            'is_hidden' => true
        ]);
        $request = ServerRequestFactory::from_globals();
        $controller = container()->get( AppSettingsController::class );
        $response = $controller->update( $request, [ 'slug' => $app['slug'] ] );
        $this->assertEquals( 302, $response->getStatusCode() );
    }

    /**
     * @test
     */
    public function it_updates_apps()
    {
        $app = $this->app_factory();

        $controller = container()->get( AppSettingsController::class );

        //Create an app
        $request = ServerRequestFactory::request( 'POST', '/admin.php?page=dt_home&tab=app&action=create', $app );
        $controller->store( $request );
        $apps = container()->get( Apps::class )->all();
        $this->assertContains( $app['slug'], array_column( $apps, 'slug' ) );

        //Update the app
        $app['name'] = 'Updated App';
        $request = ServerRequestFactory::request( 'POST', '/admin.php?page=dt_home&tab=app&action=update', $app );
        $response = $controller->update( $request, [ 'slug' => $app['slug'] ] );

        $this->assertEquals( 302, $response->getStatusCode() );
        $apps = container()->get( Apps::class )->all();
        $this->assertContains( $app['name'], array_column( $apps, 'name' ) );
    }
}
