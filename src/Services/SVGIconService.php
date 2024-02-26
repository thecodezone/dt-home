<?php

namespace DT\Home\Services;

class SVGIconService
{
    private $svg_dir_path;

    public function __construct( $svg_dir_path )
    {
        $this->svg_dir_path = $svg_dir_path;
    }

    public function get_svg_icon_urls()
    {
        if ( is_dir( $this->svg_dir_path ) ) {
            $svg_files = array_diff( scandir( $this->svg_dir_path ), [ '..', '.' ] );

            return array_values(array_filter(array_map(function ( $file ) {
                if ( pathinfo( $this->svg_dir_path . $file, PATHINFO_EXTENSION ) === 'svg' ) {
                    return get_template_directory_uri() . '/dt-assets/images/' . $file;
                }
                return false;
            }, $svg_files)));
        } else {
            // Directory not found, handle this case appropriately
            return [];
        }
    }
}
