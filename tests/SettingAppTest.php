<?php


namespace Tests;

use DT\Home\Sources\SettingsApps;
use function DT\Home\container;

class SettingAppTest extends TestCase
{

    /**
     * @test
     */
    public function it_raw()
    {
        $user_apps_services = container()->get( SettingsApps::class );
        $apps = [ app_factory(), app_factory() ];

        $save = $user_apps_services->save( $apps );
        $setting_apps = container()->get( SettingsApps::class )->raw();

        $this->assertCount( 2, $setting_apps );
    }

    /**
     * @test
     */

    public function it_is_allowed()
    {
        $apps = app_factory( [ 'is_deleted' => true ] );
        $setting_apps = container()->get( SettingsApps::class )->is_allowed( $apps );

        $this->assertFalse( $setting_apps );
    }

    /**
     * @test
     */
    public function it_save()
    {
        $apps = [ app_factory(), app_factory() ];
        $settings_apps_service = container()->get( SettingsApps::class );

        $settings_apps_service->save( $apps );
        $app_slugs = array_column( $apps, 'slug' );

        $get_apps = container()->get( SettingsApps::class )->raw();
        $get_app_slugs = array_column( $get_apps, 'slug' );

        foreach ( $app_slugs as $slug ) {
            $this->assertContains( $slug, $get_app_slugs );
        }
        $setting_apps = $settings_apps_service->raw();
        $this->assertCount( 2, $setting_apps );
    }
}
