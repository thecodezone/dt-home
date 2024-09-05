<?php

namespace Tests;

use DT\Home\CodeZone\Router\Middleware\HandleErrors;
use DT\Home\CodeZone\Router\Middleware\HandleRedirects;
use DT\Home\CodeZone\Router\Middleware\Render;
use DT\Home\CodeZone\Router\Middleware\Stack;
use DT\Home\CodeZone\WPSupport\Router\Route;
use DT\Home\CodeZone\WPSupport\Router\ServerRequestFactory;
use DT\Home\Illuminate\Http\Request;
use WP_UnitTestCase;
use Faker;
use function DT\Home\namespace_string;
use function DT\Home\container;
use function DT\Home\config;
use function DT\Home\routes_path;

/**
 * Abstract class representing a test case.
 *
 * This class extends the WP_UnitTestCase class and provides additional functionality for setting up and tearing down the test environment,
 * sending HTTP requests, and performing common test actions such as GET and POST requests.
 *
 * @since 1.29.0
 */
abstract class TestCase extends WP_UnitTestCase {
	/**
	 * The Faker instance.
	 *
	 * @var Faker\Generator
	 */
	protected Faker\Generator $faker;

    /**
     * Constructs a new instance of the class.
     *
     * @param string|null $name The name of the test case.
     * @param array $data An array of test data.
     * @param mixed|string $data_nme Additional data parameter (name).
     */
    public function __construct( ?string $name = null, array $data = [], $data_nme = '' ) {
		$this->faker = \Faker\Factory::create();
		parent::__construct( $name, $data, $data_nme );
	}

    /**
     * Sets up the test environment before executing each test method.
     *
     * @return void
     */
    public function setUp(): void {
		global $wpdb;
		$wpdb->query( 'START TRANSACTION' );
		parent::setUp();
	}

    /**
     * The tearDown method is used to clean up any resources or connections after each test case is executed.
     * In this specific case, it performs a rollback in the database using the global $wpdb variable of WordPress.
     * It then calls the tearDown method of the parent class to ensure any additional cleanup tasks are performed.
     * @return void
     */
	public function tearDown(): void {
		global $wpdb;
		$wpdb->query( 'ROLLBACK' );
		parent::tearDown();
	}

    public function as_user() {
        $user = wp_create_user( $this->faker->userName, $this->faker->password, $this->faker->email );
        $this->acting_as( $user );
        return $user;
    }

    public function acting_as( $user_id ) {
        wp_set_current_user( $user_id );
        wp_set_auth_cookie( $user_id );
    }
}
