<?php

use DT\Launcher\Controllers\Admin\GeneralSettingsController;

$r->get( 'wp-admin/admin.php?page=dt_launcher', GeneralSettingsController::class . '@show' );
$r->get( 'wp-admin/admin.php?page=dt_launcher&tab=general', GeneralSettingsController::class . '@show' );
$r->post( 'wp-admin/admin.php?page=dt_launcher', GeneralSettingsController::class . '@update' );
$r->post( 'wp-admin/admin.php?page=dt_launcher&tab=general', GeneralSettingsController::class . '@update' );
