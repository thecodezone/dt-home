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
		activate_plugin( 'dt-home/dt-home.php' );

		$this->assertContains(
			'dt-home/dt-home.php',
			get_option( 'active_plugins' )
		);
	}

	/**
	 * @test
	 */
	public function example_http_test() {
		$response = $this->get( '/home' );

		$this->assertEquals( 302, $response->getStatusCode() );
	}
}
