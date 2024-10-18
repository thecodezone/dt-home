<?php

namespace Tests;

use DT\Home\Services\Apps;
use DT\Home\Sources\SettingsApps;
use DT\Home\Sources\UserApps;
use function DT\Home\container;

class UserAppsTest extends TestCase
{
    /**
     * @test
     */
    public function it_retrieves_raw_user_apps()
    {
        $user_id = 1;
        $this->acting_as( $user_id );

        $user_apps_services = container()->get( UserApps::class );
        $apps = [ app_factory(), app_factory() ];
        $user_apps_services->save_for( $user_id, $apps );

        $user_apps = container()->get( UserApps::class )->raw( [ 'user_id' => $user_id ] );

        // Extract slugs from both arrays
        $app_slugs = array_column( $apps, 'slug' );
        $user_app_slugs = array_column( $user_apps, 'slug' );

        // Verify each slug is present in the retrieved user apps
        foreach ( $app_slugs as $slug ) {
            $this->assertContains( $slug, $user_app_slugs );
        }

        // Verify the count of retrieved user apps
        $this->assertCount( 2, $user_apps );
    }

    /**
     * @test
     */
    public function it_retrieves_all_user_apps()
    {
        $user_id = 1;
        $this->acting_as( $user_id );

        $user_apps_services = container()->get( UserApps::class );
        $apps = [ app_factory(), app_factory() ];
        $user_apps_services->save_for( $user_id, $apps );

        $user_apps = container()->get( UserApps::class )->for( $user_id );

        // Extract slugs from both arrays
        $app_slugs = array_column( $apps, 'slug' );
        $user_app_slugs = array_column( $user_apps, 'slug' );

        // Verify each slug is present in the retrieved user apps
        foreach ( $app_slugs as $slug ) {
            $this->assertContains( $slug, $user_app_slugs );
        }

        // Verify the count of retrieved user apps
        $this->assertCount( 2, $user_apps );
    }

    /**
     * @test
     */
    public function it_saves_user_apps()
    {
        $user_id = 1;
        $this->acting_as( $user_id );

        $apps = [ app_factory(), app_factory() ];
        $result = container()->get( UserApps::class )->save_for( $user_id, $apps );
        $get_apps = container()->get( UserApps::class )->for( $user_id );

        // Extract slugs from both arrays
        $app_slugs = array_column( $apps, 'slug' );
        $get_app_slugs = array_column( $get_apps, 'slug' );

        // Verify each slug is present in the retrieved apps
        foreach ( $app_slugs as $slug ) {
            $this->assertContains( $slug, $get_app_slugs );
        }
        // Verify the saving operation was successful
        $this->assertTrue( $result );
    }

    /**
     * @test
     */
    public function it_saves_user_apps_with_default_user_id()
    {
        $user_id = 1;
        $this->acting_as( $user_id );

        $apps = [ app_factory(), app_factory() ];
        $result = container()->get( UserApps::class )->save( $apps );
        $get_apps = container()->get( UserApps::class )->for( $user_id );

        // Extract slugs from both arrays
        $app_slugs = array_column( $apps, 'slug' );
        $get_app_slugs = array_column( $get_apps, 'slug' );

        // Verify each slug is present in the retrieved apps
        foreach ( $app_slugs as $slug ) {
            $this->assertContains( $slug, $get_app_slugs );
        }
        // Verify the saving operation was successful
        $this->assertTrue( $result );
    }

    /**
     * @test
     */
    public function it_merge_user_apps()
    {
        $user_id = 1;
        $this->acting_as( $user_id );

        // Existing apps
        $setting_apps_services = container()->get( SettingsApps::class );
        $existing_apps = $setting_apps_services->all();

        // New app to merge with a specific value
        $new_app = [ app_factory( [ 'slug' => 'new-app-slug' ] ) ];

        // Expected result after merge
        $expected_merged_apps = array_merge( $existing_apps, $new_app );

        // Perform the merge
        $user_apps_services = container()->get( UserApps::class );
        $merged_apps = $user_apps_services->merge( $existing_apps, $new_app );

        // Assert that the merged apps match the expected result
        $this->assertEquals( $expected_merged_apps, $merged_apps );

        // Assert that the new app's slug is present in the merged apps
        $merged_slugs = array_column( $merged_apps, 'slug' );

        $this->assertContains( 'new-app-slug', $merged_slugs );
    }


    /**
     * @test
     */
    public function it_hides_user_apps()
    {
        $user_id = 1;
        $this->acting_as( $user_id );

        // Get the first app
        $apps_service = container()->get( Apps::class );
        $apps = $apps_service->for( $user_id );
        $data = $apps[0];

        // Hide the app
        $source = container()->get( UserApps::class );
        $hiddenItem = $source->hide( $data['slug'] );

        // Assert that the item is hidden
        $this->assertTrue( $hiddenItem['is_hidden'] );

        // Assert that the item is found
        $foundItem = $source->find( $data['slug'] );
        $this->assertEquals( $hiddenItem, $foundItem );
    }

    /**
     * @test
     */
    public function it_unhide_user_apps()
    {
        $user_id = 1;
        $this->acting_as( $user_id );

        // Get the first app
        $apps_service = container()->get( Apps::class );
        $apps = $apps_service->for( $user_id );
        $data = $apps[0];

        // Hide the app
        $source = container()->get( UserApps::class );
        $hiddenItem = $source->hide( $data['slug'] );

        // Unhide the app
        $unhiddenItem = $source->unhide( $data['slug'] );

        // Assert that the item is unhidden
        $this->assertFalse( $unhiddenItem['is_hidden'] );

        // Assert that the item is found
        $foundItem = $source->find( $data['slug'] );
        $this->assertEquals( $unhiddenItem, $foundItem );
    }

    /**
     * @test
     */
    public function it_checks_if_app_is_visible()
    {
        $user_id = 1;
        $this->acting_as( $user_id );

        // Get the first app
        $apps_service = container()->get( Apps::class );
        $apps = $apps_service->for( $user_id );
        $data = $apps[0];

        // Hide the app
        $source = container()->get( UserApps::class );
        $hiddenItem = $source->hide($data['slug']
        );
        // Assert that the item is hidden
        $this->assertTrue( $hiddenItem['is_hidden'] );

        // Assert that the item is hidden
        $this->assertTrue( $source->is_visible( $data ) );
    }

    /**
     * @test
     */
    public function it_checks_if_app_is_hidden()
    {
        $user_id = 1;
        $this->acting_as( $user_id );

        // Create apps with different visibility statuses
        $apps = [
            app_factory( [ 'slug' => 'app1', 'is_hidden' => false ] ),
            app_factory( [ 'slug' => 'app2', 'is_hidden' => true ] ),
            app_factory( [ 'slug' => 'app3', 'is_hidden' => false ] ),
            app_factory( [ 'slug' => 'app4', 'is_hidden' => true ] )
        ];

        $source = container()->get( UserApps::class );

        // Save the apps
        $source->save_for( $user_id, $apps );

        // Retrieve all apps
        $allApps = $source->for( $user_id );

        // Extract slugs from both arrays
        $app_slugs = array_column( $apps, 'slug' );
        $user_app_slugs = array_column( $allApps, 'slug' );

        // Verify each slug is present in the retrieved user apps
        foreach ( $app_slugs as $slug ) {
            $this->assertContains( $slug, $user_app_slugs );
        }

        // Filter hidden apps
        $hiddenApps = $source->hidden( $allApps );

        // Assert that only hidden apps are in the hiddenApps array
        $this->assertCount( 2, $hiddenApps );

        // Assert that all hidden apps are hidden
        foreach ( $hiddenApps as $app ) {
            $this->assertTrue( $app['is_hidden'] );
        }
    }

    /**
     * @test
     */
    public function it_retrieves_all_deleted_apps()

    {
        $user_id = 1;
        $this->acting_as( $user_id );

        // Create apps with different visibility statuses
        $apps = [
            app_factory( [ 'slug' => 'app1', 'is_deleted' => false ] ),
            app_factory( [ 'slug' => 'app2', 'is_deleted' => true ] ),
            app_factory( [ 'slug' => 'app3', 'is_deleted' => false ] ),
            app_factory( [ 'slug' => 'app4', 'is_deleted' => true ] )
        ];

        $source = container()->get( UserApps::class );

        // Save the apps
        $source->save_for( $user_id, $apps );

        // Retrieve all apps
        $allApps = $source->for( $user_id );

        // Filter deleted apps
        $deletedApps = $source->deleted( $allApps );

        // Assert that only hidden apps are in the deletedApps array
        $this->assertCount( 2, $deletedApps );

        // Assert that all deleted apps are deleted
        foreach ( $deletedApps as $app ) {
            $this->assertTrue( $app['is_deleted'] );
        }
    }

    /**
     * @test
     */
    public function it_retrieves_all_undeleted_apps()
    {
        $user_id = 1;
        $this->acting_as( $user_id );

        // Create apps with different visibility statuses
        $apps = [
            app_factory( [ 'slug' => 'app1', 'is_deleted' => false ] ),
            app_factory( [ 'slug' => 'app2', 'is_deleted' => true ] ),
            app_factory( [ 'slug' => 'app3', 'is_deleted' => false ] ),
            app_factory( [ 'slug' => 'app4', 'is_deleted' => true ] ),
            app_factory( [ 'slug' => 'app5', 'is_deleted' => false ] ),
        ];

        $source = container()->get( UserApps::class );

        // Save the apps
        $source->save_for( $user_id, $apps );
        // Retrieve all apps
        $allApps = $source->for( $user_id );
        // Filter undeleted apps
        $undeletedApps = $source->undeleted( $allApps );
        // Assert that only hidden apps are in the undeletedApps array
        $this->assertCount( 3, $undeletedApps );
        // Assert that all undeleted apps are undeleted
        foreach ( $undeletedApps as $app ) {
            $this->assertFalse( $app['is_deleted'] );
        }
    }

    /**
     * @test
     */
    public function it_soft_deletes_an_item()
    {
        $user_id = 1;
        $this->acting_as( $user_id );

        // Create apps with different visibility statuses
        $apps = [
            app_factory( [ 'slug' => 'test-app1', 'is_deleted' => false ] ),
            app_factory( [ 'slug' => 'test-app2', 'is_deleted' => false ] ),
        ];

        $source = container()->get( UserApps::class );
        // Save the apps
        $source->save_for( $user_id, $apps );

        // Soft delete an item
        $slug = 'test-app1';
        $soft_delete_app = $source->delete( $slug );
        $this->assertTrue( $soft_delete_app );

        // Retrieve all apps
        $allApps = $source->for( $user_id );

        // Filter deleted apps
        $deletedApps = $source->deleted( $allApps );

        // Assert that only hidden apps are in the deletedApps array
        $this->assertCount( 1, $deletedApps );

        $foundItem = $source->find( 'test-app1' );
        $this->assertTrue( $foundItem['is_deleted'] );
    }

    /**
     * @test
     */
    public function it_destroy_app()
    {
        $user_id = 1;
        $this->acting_as( $user_id );

        // Create apps with different visibility statuses
        $apps = [
            app_factory( [ 'slug' => 'app1' ] ),
            app_factory( [ 'slug' => 'app2' ] ),
        ];

        $source = container()->get( UserApps::class );
        // Save the apps
        $source->save_for( $user_id, $apps );

        // Soft delete an item
        $slug = 'app1';
        $destroy_app = $source->destroy( $slug );

        $this->assertTrue( $destroy_app );

        // Retrieve all apps
        $allApps = $source->for( $user_id );

        // Filter deleted apps
        $deletedApps = $source->deleted( $allApps );

        // Assert that only hidden apps are in the deletedApps array
        $this->assertCount( 0, $deletedApps );

        $foundItem = $source->find( 'app1' );
        $this->assertNotContains( $foundItem, $allApps );
    }

    /**
     * @test
     */
    public function it_finds_an_item()
    {
        $user_id = 1;
        $this->acting_as( $user_id );

        // Create apps with different visibility statuses
        $apps = [
            app_factory( [ 'slug' => 'app1', 'source' => 'user' ] ),
            app_factory( [ 'slug' => 'app2', 'source' => 'user' ] ),
        ];

        $source = container()->get( UserApps::class );
        // Save the apps
        $source->save_for( $user_id, $apps );

        $foundItem = $source->find( 'app1' );

        $this->assertEquals( $foundItem, $apps[0] );
    }

    /**
     * @test
     */
    public function it_allowed_an_app()
    {
        $user_id = 1;
        $this->acting_as( $user_id );

        $apps = [
            app_factory( [ 'slug' => 'app1' ] ),
            app_factory( [ 'slug' => 'app2' ] ),
        ];

        $source = container()->get( UserApps::class );
        $allowed = $source->is_allowed( $apps );

        $this->assertTrue( $allowed );
    }

    /**
     * @test
     */
    public function it_disallowed_an_app()
    {
        $user_id = 1;
        $this->acting_as( $user_id );

        $apps = [
            app_factory( [ 'slug' => 'app1' ] ),
            app_factory( [ 'slug' => 'app2' ] )
        ];

        $source = container()->get( UserApps::class );
        $disallowed = $source->disallowed( $apps );

        // Assert that the disallowed array contains only apps that are not allowed
        $this->assertCount( 0, $disallowed );
    }

    /**
     * @test
     */
    public function it_filter_an_app()
    {
        $user_id = 1;
        $this->acting_as( $user_id );

        $apps = [
            app_factory( [ 'slug' => 'app1' ] ),
            app_factory( [ 'slug' => 'app2' ] )
        ];

        $source = container()->get( UserApps::class );
        $filtered = $source->filter( $apps );

        // Assert that the filtered array contains only the app that matches the filter
        $this->assertEquals( 'app1', $filtered[0]['slug'] );
    }

    /**
     * @test
     */
    public function it_retrieves_all_apps()
    {
        $user_id = 1;
        $this->acting_as( $user_id );

        $apps = [
            app_factory( [ 'slug' => 'app1' ] ),
            app_factory( [ 'slug' => 'app2' ] )
        ];

        $source = container()->get( UserApps::class );
        $source->save_for( $user_id, $apps );
        $allApps = $source->all( [ 'filter' => false ] );

        $app_slugs = array_column( $apps, 'slug' );
        $user_app_slugs = array_column( $allApps, 'slug' );

        // Verify each slug is present in the retrieved user apps
        foreach ( $app_slugs as $slug ) {
            $this->assertContains( $slug, $user_app_slugs );
        }
        // Assert that the allApps array contains all the apps
        $this->assertCount( 2, $allApps );
    }

    /**
     * @test
     */
    public function it_fetch_for_save_an_app()
    {
        $user_id = 1;
        $this->acting_as( $user_id );

        $apps = [
            app_factory( [ 'slug' => 'app1' ] )
        ];

        $source = container()->get( UserApps::class );
        $source->save_for( $user_id, $apps );
        $fetch_for_save = $source->fetch_for_save();

        $app_slugs = array_column( $apps, 'slug' );
        $fetch_for_save_slugs = array_column( $fetch_for_save, 'slug' );

        // Verify each slug is present in the retrieved user apps
        foreach ( $app_slugs as $slug ) {
            $this->assertContains( $slug, $fetch_for_save_slugs );
        }
    }

    /**
     * @test
     */
    public function it_merged_for_all_apps()
    {
        $user_id = 1;
        $this->acting_as( $user_id );

        $apps = [
            app_factory( [ 'slug' => 'app1' ] ),
        ];

        $source = container()->get( UserApps::class );
        $source->save_for( $user_id, $apps );

        $merged = $source->merged();

        $app_slugs = array_column( $apps, 'slug' );
        $merged_slugs = array_column( $merged, 'slug' );

        // Verify each slug is present in the retrieved user apps
        foreach ( $app_slugs as $slug ) {
            $this->assertContains( $slug, $merged_slugs );
        }
    }
}
