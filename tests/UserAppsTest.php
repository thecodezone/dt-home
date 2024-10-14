<?php

namespace Tests;

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
}
