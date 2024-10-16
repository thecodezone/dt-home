<?php

namespace DT\Home\Services;

use DT\Home\Sources\SettingsApps;
use function DT\Home\container;
use function DT\Home\get_plugin_option;

/**
 * Manage D.T Roles & Permissions.
 */
class RolesPermissions {

    public const CAPABILITIES_SOURCE = 'Home Screen';
    public const OPTION_KEY_CUSTOM_ROLES = 'dt_custom_roles';

    public function __construct() {}

    /**
     * Initialise and hook in to various filters and actions.
     * @return void
     */
    public function init(): void {
        add_filter( 'dt_capabilities', [ $this, 'dt_capabilities' ], 50, 1 );
        add_filter( 'dt_set_roles_and_permissions', [ $this, 'dt_set_roles_and_permissions' ], 10, 1 );
    }

    /**
     * Default D.T Home Screen capabilities.
     * @return array
     */
    private function default_capabilities(): array {
        $capabilities = [
            'can_access_home_screen' => [
                'source' => self::CAPABILITIES_SOURCE,
                'description' => ''
            ]
        ];

        // Capture available apps and build associated capabilities.
        foreach ( container()->get( SettingsApps::class )->all() ?? [] as $app ) {
            if ( isset( $app['slug'] ) ) {
                $capabilities[ $this->generate_permission_key( $app['slug'] ) ] = [
                    'source' => self::CAPABILITIES_SOURCE,
                    'description' => ''
                ];
            }
        }

        return $capabilities;
    }

    /**
     * Register plugin specific D.T Capabilities.
     * @param $capabilities
     * @return array
     */
    public function dt_capabilities( $capabilities ): array {
        if ( get_plugin_option( 'dt_home_use_capabilities', false ) ) {
            $capabilities = array_merge( $capabilities, $this->default_capabilities() );
        }

        return $capabilities;
    }

    /**
     * Default D.T Home Screen role and permission assignments.
     * @return array
     */
    private function default_roles_and_permissions(): array {
        $default_roles = [
            'administrator',
            'custom_developer',
            'dispatcher',
            'dt_admin',
            'multiplier'
        ];

        // Pair default roles with capabilities; in an initial selected state.
        $default_roles_and_permissions = [];
        $default_capabilities = $this->default_capabilities();
        foreach ( $default_roles as $role ) {
            $default_roles_and_permissions[ $role ] = [];
            foreach ( array_keys( $default_capabilities ) as $capability ) {
                $default_roles_and_permissions[ $role ][ $capability ] = true;
            }
        }

        return $default_roles_and_permissions;
    }

    /**
     * Register plugin specific D.T Roles & Permissions.
     * @param $expected_roles
     * @return array
     */
    public function dt_set_roles_and_permissions( $expected_roles ): array {
        if ( get_plugin_option( 'dt_home_use_capabilities', false ) ) {
            $dt_custom_roles = get_option( self::OPTION_KEY_CUSTOM_ROLES, [] );

            /** $dt_custom_roles_updated = false; **/
            foreach ( $this->default_roles_and_permissions() as $role => $permissions ) {
                if ( !is_array( $expected_roles[$role]['permissions'] ) ) {
                    $expected_roles[$role]['permissions'] = [];
                }

                /**
                 * Ensure selected flag is set accordingly, based on saved
                 * custom role settings; which take priority.
                 */

                foreach ( $permissions as $permission => $selected ) {
                    $expected_roles[$role]['permissions'][$permission] = $dt_custom_roles[$role]['capabilities'][$permission] ?? $selected;

                    /**
                     * If no corresponding custom roles settings detected,
                     * capture and persist; to ensure selected flag state is made
                     * available further downstream for processing.
                     *

                    if ( !is_array( $dt_custom_roles[$role]['capabilities'] ) ) {
                    $dt_custom_roles[$role]['capabilities'] = [];
                    }

                    if ( !isset( $dt_custom_roles[$role]['capabilities'][$permission] ) ) {
                    $dt_custom_roles_updated = true;
                    $dt_custom_roles[$role]['capabilities'][$permission] = $expected_roles[$role]['permissions'][$permission];
                    }
                     ***/
                }
            }

            /**if ( $dt_custom_roles_updated ) {
            update_option( self::OPTION_KEY_CUSTOM_ROLES, $dt_custom_roles );
            }**/
        }

        return $expected_roles;
    }

    /**
     * Build associated permission key, based on specified slug and type.
     *
     * @param string $slug
     * @param string $type
     * @return string
     */
    public function generate_permission_key( string $slug, string $type = 'access' ): string {
        switch ( $type ) {
            case 'access':
            default:
                return 'can_access_'. $slug .'_app';
        }
    }

    /**
     * Update global user roles for specified permissions.
     *
     * @param string $app_slug
     * @param array $permissions
     * @param array $roles
     * @param array $deleted_roles
     * @return bool
     */
    public function update( string $app_slug, array $permissions, array $roles = [], array $deleted_roles = [] ): bool {
        $dt_custom_roles = array_map( function( $custom_role ) use ( $permissions, $roles, $deleted_roles ) {
            if ( isset( $custom_role['slug'] ) ) {
                $custom_role_slug = $custom_role['slug'];

                // Update specified role permissions.
                if ( in_array( $custom_role_slug, $roles ) ) {
                    if ( !isset( $custom_role['capabilities'] ) ) {
                        $custom_role['capabilities'] = [];
                    }

                    foreach ( $permissions as $permission ) {
                        $custom_role['capabilities'][$permission] = true;
                    }
                }

                // Delete specified role permissions.
                if ( in_array( $custom_role_slug, $deleted_roles ) ) {
                    if ( isset( $custom_role['capabilities'] ) ) {
                        foreach ( $permissions as $permission ) {
                            $custom_role['capabilities'][$permission] = false;
                        }
                    }
                }
            }

            return $custom_role;

        }, get_option( self::OPTION_KEY_CUSTOM_ROLES, [] ) );

        // Persist updated global custom roles.
        return update_option( self::OPTION_KEY_CUSTOM_ROLES, $dt_custom_roles );
    }
}
