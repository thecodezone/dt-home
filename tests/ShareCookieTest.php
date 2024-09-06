<?php

namespace Tests;

use DT\Home\CodeZone\WPSupport\Router\Dispatcher;
use DT\Home\CodeZone\WPSupport\Router\ServerRequestFactory;
use DT\Home\Middleware\CheckShareCookie;
use function DT\Home\config;
use function DT\Home\response;

class ShareCookieTest extends TestCase {

    /**
     * Test that the middleware does not call the add_leader method if the cookie is not set
     * @test
     */
    public function it_does_not_set_leader_without_cookie() {
        $middleware = $this->createPartialMock( CheckShareCookie::class, [ 'add_leader' ] );
        $middleware->expects( $this->never() )->method( 'add_leader' );
        $dispatcher = $this->createMock( Dispatcher::class );
        $dispatcher->expects( $this->once() )
            ->method( 'handle' )
            ->willReturn( response( 'OK' ) );
        $middleware->process(
            ServerRequestFactory::from_globals(),
            $dispatcher
        );
    }

    /**
     * Test that the middleware calls the add_leader method if the cookie is set
     * @test
     */
    public function it_sets_leader_with_cookie() {
        $user = wp_create_user( $this->faker->userName, $this->faker->password, $this->faker->email );
        wp_set_current_user( $user );
        wp_set_auth_cookie( $user );
        $middleware = $this->createPartialMock( CheckShareCookie::class, [ 'add_leader' ] );
        $middleware->expects( $this->once() )->method( 'add_leader' );
        $_COOKIE[ config( 'plugin.share_cookie' ) ] = '123';
        $dispatcher = $this->createMock( Dispatcher::class );
        $dispatcher->expects( $this->once() )
            ->method( 'handle' )
            ->willReturn( response( 'OK' ) );
        $middleware->process(
            ServerRequestFactory::from_globals(),
            $dispatcher
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

        $middleware = $this->createPartialMock( CheckShareCookie::class, [ 'remove_cookie' ] );
        $middleware->method( 'remove_cookie' )->willReturn( null );
        $middleware->add_leader( $leader_contact );



        //Check that the leader was added to the user properly
        $contact = \DT_Posts::get_post( 'contacts', $user_contact );
        $this->assertEquals( $leader_contact, $contact['coached_by'][0]['ID'] );
        $this->assertEquals( $leader, $contact['assigned_to']['id'] );
    }
}
