<?php
/**
 * phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
 */

use function DT\Home\view;

/**
 * Renders the back button on the home page.
 *
 * @return void
 */
function dt_home_back_button(): void
{
   echo (string) view( 'partials/return-to-launcher-button' )->getBody();
}
