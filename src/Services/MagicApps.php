<?php

namespace DT\Home\Services;

/**
 * Magic apps service provider. Handlers the automatic registration of magic apps.
 */
class MagicApps {
    public function __construct()
    {
        add_filter( 'dt_home_apps', [ $this, 'register' ], 999 );
    }

    /**
     * Retrieve all magic URL register types.
     *
     * @return array Returns an array of magic URL register types.
     */
    public function all() {
        return apply_filters( 'dt_magic_url_register_types', [] );
    }

    /**
     * Automatically register custom magic apps
     *
     * @param array $apps The apps to be saved.
     * @return bool Whether the saving was successful or not.
     */
    public function register( array $apps ): array {
        foreach ( $this->unregistered_magic_apps() as $app ){

            // If we find a value with the slug in the apps array, skip
            $match = array_filter( $apps, function ( $a ) use ( $app ) {
                return $a['slug'] === $app['slug'];
            } );

            if ( !empty( $match ) ){
                continue;
            }

            $apps[] = $app;
        }

        return $apps;
    }

    /**
     * Retrieve, filter and format unregistered magic apps
     *
     * @return array
     */
    private function unregistered_magic_apps(): array {
        $magic_apps = [];
        foreach ( $this->all() as $root_key => $root_value ){
            foreach ( $root_value as $type_key => $app ){
                // Skip if app is not set to show in home apps
                if ( empty( $app['meta']['show_in_home_apps'] ) ){
                    continue;
                }

                $magic_apps[$app['type']] = array_merge( [
                    'name' => $app['label'],
                    'type' => 'Web View',
                    'creation_type' => 'code',
                    'icon' => $app['meta']['icon'] ?? '/wp-content/themes/disciple-tools-theme/dt-assets/images/link.svg',
                    'url' => trailingslashit( trailingslashit( site_url() ) . $app['url_base'] ),
                    'slug' => $app['type'],
                    'sort' => $app['sort'] ?? 10,
                    'is_hidden' => false,
                    'open_in_new_tab' => false,
                    'magic_link_meta' => [
                        'post_type' => $app['post_type'],
                        'root' => $app['root'],
                        'type' => $app['type']
                    ]
                ], $magic_apps[$app['type']] ?? [] );
            }
        }

        return array_values( $magic_apps );
    }
}
