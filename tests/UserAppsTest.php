<?php

namespace Tests;

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
        $this->assertTrue( $result );
    }

    /**
     * @test
     */
    public function it_merge_user_apps()
    {
        $user_id = 1;
        $this->acting_as( $user_id );

        $user_apps_services = container()->get( UserApps::class );
        $user_apps = $user_apps_services->raw( [ 'user_id' => $user_id ] );

        $new_apps = [ app_factory() ];
        $merged_apps = $user_apps_services->merge( $user_apps, $new_apps );

        // Assert that the count of merged apps is the sum of original and new apps
        $this->assertCount( count( $user_apps ) + count( $new_apps ), $merged_apps );
    }
}
