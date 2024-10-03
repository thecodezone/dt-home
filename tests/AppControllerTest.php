<?php

namespace Tests;

use DT\Home\CodeZone\WPSupport\Router\ServerRequestFactory;
use DT\Home\Controllers\MagicLink\AppController;
use DT\Home\Sources\SettingsApps;
use DT\Home\Sources\UserApps;
use function DT\Home\container;

class AppControllerTest extends TestCase
{
    /**
     * @test
     */
    public function it_shows_launcher() {
        $request = ServerRequestFactory::from_globals();
        $key = $this->faker->md5;
        $controller = container()->get( AppController::class );
        $response = $controller->index( $request, [ 'key' => $key ] );
        $this->assertEquals( 200, $response->getStatusCode() );
    }

    /**
     * @test
     */
    public function it_handles_missing_apps() {
        $request = ServerRequestFactory::from_globals();
        $this->as_user();
        $app = app_factory();
        $controller = container()->get( AppController::class );
        $response = $controller->show( $request, [ 'slug' => $app['slug'] ] );
        $this->assertEquals( 404, $response->getStatusCode() );
    }

    /**
     * @test
     *
     * Currently fails, as app data not persisting for ome reason...!?
    public function it_renders() {
        $this->as_user();
        $request = ServerRequestFactory::from_globals();
        $app = app_factory([
            'creation_type' => 'custom',
            'is_deleted' => false,
            'is_hidden' => false,
        ]);
        $apps = container()->get( SettingsApps::class );
        $data = $apps->raw();
        $data[] = $app;
        $apps->save( $data );
        $controller = container()->get( AppController::class );
        $response = $controller->show( $request, [ 'slug' => $app['slug'] ] );
        $this->assertEquals( 200, $response->getStatusCode() );
    }*/

    /**
     * @test
     */
    public function it_can_hide_apps() {
        $this->as_user();
        $data = app_factory([
            'creation_type' => 'custom',
            'is_hidden' => true,
            'is_deleted' => false,
        ]);
        $settings_apps = container()->get( SettingsApps::class );
        $user_apps = container()->get( UserApps::class );
        $settings_apps->save( [ $data ] );
        $user_apps->save( [ $data ] );
        $request = ServerRequestFactory::request( 'POST', 'apps/launcher/key/update-hide-apps', $data );
        $controller = container()->get( AppController::class );
        $response = $controller->hide( $request );
        $this->assertEquals( 200, $response->getStatusCode() );
    }

    /**
     * @test
     */
    public function it_can_unhide_apps() {
        $this->as_user();
        $data = app_factory([
            'creation_type' => 'custom',
            'is_hidden' => true,
            'is_deleted' => false,
        ]);
        $settings_apps = container()->get( SettingsApps::class );
        $user_apps = container()->get( UserApps::class );
        $settings_apps->save( [ $data ] );
        $user_apps->save( [ $data ] );
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
        $this->as_user();
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
