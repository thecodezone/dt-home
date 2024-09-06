<?php

namespace Tests;

use DT\Home\CodeZone\WPSupport\Router\ServerRequestFactory;
use DT\Home\Controllers\Admin\TrainingSettingsController;
use function DT\Home\container;
use function DT\Home\get_plugin_option;

class TrainingSettingsControllerTest extends TestCase
{
    /**
     * @test
     */
    public function it_renders()
    {
        $request = ServerRequestFactory::from_globals();
        $controller = container()->get( TrainingSettingsController::class );
        $response = $controller->show( $request );
        $this->assertEquals( 200, $response->getStatusCode() );
    }

    /**
     * @test
     */
    public function it_renders_create()
    {
        $request = ServerRequestFactory::from_globals();
        $controller = container()->get( TrainingSettingsController::class );
        $response = $controller->create( $request );
        $this->assertEquals( 200, $response->getStatusCode() );
    }

    /**
     * @test
     */
    public function it_creates()
    {
        $training = training_factory();
        $request = ServerRequestFactory::request( 'POST', '/admin.php?page=dt_home&tab=training&action=create', $training );
        $controller = container()->get( TrainingSettingsController::class );
        $response = $controller->store( $request );
        $this->assertEquals( 302, $response->getStatusCode() );
        $trainings = get_plugin_option( 'trainings' );
        $this->assertContains( $training['name'], array_column( $trainings, 'name' ) );
    }

    /**
     * @test
     */
    public function it_renders_edit() {
        $training = training_factory();

        //Create
        $request = ServerRequestFactory::request( 'POST', '/admin.php?page=dt_home&tab=training&action=create', $training );
        $controller = container()->get( TrainingSettingsController::class );
        $response = $controller->store( $request );
        $this->assertEquals( 302, $response->getStatusCode() );
        $trainings = get_plugin_option( 'trainings' );
        $this->assertContains( $training['name'], array_column( $trainings, 'name' ) );
        $training = $trainings[array_search( $training['name'], array_column( $trainings, 'name' ) )];

        //Render
        $training = $trainings[array_search( $training['name'], array_column( $trainings, 'name' ) )];
        $request = ServerRequestFactory::request( 'GET', '/admin.php?page=dt_home&tab=training&action=edit/' . $training['id'] );
        $response = $controller->edit( $request, [ 'id' => $training['id'] ] );
        $this->assertEquals( 200, $response->getStatusCode() );
    }

    /**
     * @test
     */
    public function it_updates()
    {
        $training = training_factory();

        //Create
        $request = ServerRequestFactory::request( 'POST', '/admin.php?page=dt_home&tab=training&action=create', $training );
        $controller = container()->get( TrainingSettingsController::class );
        $response = $controller->store( $request );
        $this->assertEquals( 302, $response->getStatusCode() );
        $trainings = get_plugin_option( 'trainings' );
        $this->assertContains( $training['name'], array_column( $trainings, 'name' ) );
        $training = $trainings[array_search( $training['name'], array_column( $trainings, 'name' ) )];

        //Edit
        $training['name'] = 'New Name';
        $training['edit_id'] = $training['id'];
        $request = ServerRequestFactory::request( 'POST', '/admin.php?page=dt_home&tab=training&action=edit/' . $training['id'], $training );
        $response = $controller->update( $request, [ 'id' => $training['id'] ] );
        $this->assertEquals( 302, $response->getStatusCode() );
        $trainings = get_plugin_option( 'trainings' );
        $this->assertContains( 'New Name', array_column( $trainings, 'name' ) );
    }

    /**
     * @test
     */
    public function it_deletes()
    {
        $training = training_factory();

        //Create
        $request = ServerRequestFactory::request( 'POST', '/admin.php?page=dt_home&tab=training&action=create', $training );
        $controller = container()->get( TrainingSettingsController::class );
        $response = $controller->store( $request );
        $this->assertEquals( 302, $response->getStatusCode() );
        $trainings = get_plugin_option( 'trainings' );
        $this->assertContains( $training['name'], array_column( $trainings, 'name' ) );
        $training = $trainings[array_search( $training['name'], array_column( $trainings, 'name' ) )];

        //Delete
        $request = ServerRequestFactory::request( 'GET', '/admin.php?page=dt_home&tab=training&action=delete/' . $training['id'] );
        $response = $controller->delete( $request, [ 'id' => $training['id'] ] );
        $this->assertEquals( 302, $response->getStatusCode() );
        $trainings = get_plugin_option( 'trainings' );
        $this->assertNotContains( $training['name'], array_column( $trainings, 'name' ) );
    }
}
