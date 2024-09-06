<?php
/**
 * phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
 */

use function DT\Home\view;
function dt_home_back_button(): void
{
   echo (string) view( 'partials/return-to-launcher-button' )->getBody();
}
