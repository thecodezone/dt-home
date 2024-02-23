<?php

namespace DT\Home\Services;

class SVGIconService
{
    private $svgDirPath;

    public function __construct( $svgDirPath )
    {
        $this->svgDirPath = $svgDirPath;
    }

    public function getSVGIconURLs()
    {
        if ( is_dir( $this->svgDirPath ) ) {
            $svgFiles = array_diff( scandir( $this->svgDirPath ), [ '..', '.' ] );

            return array_values(array_filter(array_map(function ( $file ) {
                if ( pathinfo( $this->svgDirPath . $file, PATHINFO_EXTENSION ) === 'svg' ) {
                    return get_template_directory_uri() . '/dt-assets/images/' . $file;
                }
                return false;
            }, $svgFiles)));
        } else {
            // Directory not found, handle this case appropriately
            return [];
        }
    }
}
