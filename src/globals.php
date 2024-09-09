<?php

// File: src/globals.php

use function DT\Home\view;

function dt_home_back_button(): string
{
    if ( isset( $_GET['dt_home'] ) && $_GET['dt_home'] === 'true' ) {
        ob_start();
        echo view( 'partials/return-to-launcher-button' );
        return ob_get_clean();
    }
    return '';
}
