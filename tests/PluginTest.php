<?php

namespace Tests;

class PluginTest extends TestCase {
	public function test_plugin_installed() {
		activate_plugin( 'dt-launcher/dt-launcher.php' );

		$this->assertContains(
			'dt-launcher/dt-launcher.php',
			get_option( 'active_plugins' )
		);
	}
}
