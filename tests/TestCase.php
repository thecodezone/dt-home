<?php

namespace Tests;

use WP_UnitTestCase;

abstract class TestCase extends WP_UnitTestCase {
	public function setUp(): void {
		global $wpdb;
		$wpdb->query( 'START TRANSACTION' );
		parent::setUp();
	}

	public function tearDown(): void {
		global $wpdb;
		$wpdb->query( 'ROLLBACK' );
		parent::tearDown();
	}
}
