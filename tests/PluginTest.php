<?php

namespace Tests;

/**
 * @test
 */
class PluginTest extends TestCase {
	/**
	 * @test
	 */
	public function can_install() {
		activate_plugin( 'dt-plugin/dt-plugin.php' );

		$this->assertContains(
			'dt-launcher/dt-launcher.php',
			get_option( 'active_plugins' )
		);
	}

	/**
	 * @test
	 */
	public function example_http_test() {
		$response = $this->get( 'dt/plugin/api/hello' );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertStringContainsString( 'Hello World!', $response->getContent() );
	}
}
