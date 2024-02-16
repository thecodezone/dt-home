<?php

namespace Tests;

use DT\Home\Illuminate\Http\Request;
use DT\Home\Illuminate\Http\Response;
use DT\Home\Middleware\CheckShareCookie;

use function DT\Home\container;

class ShareCookieTest extends TestCase {

	/**
	 * Test that the middleware does not call the add_leader method if the cookie is not set
	 * @test
	 */
	public function it_does_not_set_leader_without_cookie() {
		$middleware = $this->createPartialMock( CheckShareCookie::class, [ 'add_leader' ] );
		$middleware->expects( $this->never() )->method( 'add_leader' );
		$middleware->handle(
			container()->make( Request::class ),
			container()->make( Response::class ),
			function () {
			}
		);
	}

	/**
	 * Test that the middleware calls the add_leader method if the cookie is set
	 * @test
	 */
	public function it_sets_leader_with_cookie() {
		$middleware = $this->createPartialMock( CheckShareCookie::class, [ 'add_leader' ] );
		$middleware->expects( $this->once() )->method( 'add_leader' );
		$request = container()->make( Request::class );
		$request->cookies->set( 'dt_home_share', '123' );
		$middleware->handle(
			$request,
			container()->make( Response::class ),
			function () {
			}
		);
	}


	/**
	 * Test that the add_leader method attaches the leader to the user properly
	 * @test
	 */
	public function it_attaches_cookie_leader() {

		//Create a fake user and log them in with the correct permissions
		$user = wp_create_user( $this->faker->userName, $this->faker->password, $this->faker->email );
		wp_set_current_user( $user );
		wp_set_auth_cookie( $user );
		add_filter( 'user_has_cap', function ( $allcaps, $caps, $args ) {
			$allcaps['view_any_contacts'] = true;
			$allcaps['access_contacts']   = true;

			return $allcaps;
		}, 100, 3 );
		$user_contact = \Disciple_Tools_Users::get_contact_for_user( $user );

		//Create a fake leader
		$leader         = wp_create_user( $this->faker->userName, $this->faker->password, $this->faker->email );
		$leader_contact = \Disciple_Tools_Users::get_contact_for_user( $leader );

		// run the middleware
		$middleware = container()->make( CheckShareCookie::class );
		$middleware->add_leader( $leader_contact );

		//Check that the leader was added to the user properly
		$contact = \DT_Posts::get_post( 'contacts', $user_contact );
		$this->assertEquals( $leader_contact, $contact['coached_by'][0]['ID'] );
		$this->assertEquals( $leader, $contact['assigned_to']['id'] );
	}
}
