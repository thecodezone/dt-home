<?php

namespace DT\Home\MagicLinks;

/**
 * Class StarterMagicApp
 *
 * Represents the Starter Magic App for handling magic links.
 */
class Launcher extends MagicLink {

	public $page_title = 'DT Home';
	public $page_description = 'DT home screen.';
	public $root = 'dt-home';
	public $type = 'launcher';
	public $post_type = 'user';
	public $show_bulk_send = true;
	public $show_app_tile = true;

    public $login_whitelist = [
        'share'
    ];

	/**
	 * Called if the route is a magic link route.
	 *
	 * If the 'dt_home_require_login' option is set to false and the user ID is available,
	 * set the current WordPress user to the fetched user ID.
	 *
	 * @return void
	 */
	public function boot() {
		if (
            ! ( get_option( 'dt_home_require_login', true ) && $this->get_user_id() )
            || in_array( $this->get_current_action(), $this->login_whitelist )
        ) {
			wp_set_current_user( $this->get_user_id() );
		}
	}

	/**
	 * Fetch and return the user ID from the 'post_id' key of the parts array.
	 * If the 'post_id' key does not exist, return null.
	 *
	 * @return mixed|null The user ID or null if 'post_id' key does not exist.
	 */
	public function get_user_id() {
		return $this->parts['post_id'] ?? null;
	}
}
