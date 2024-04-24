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
	public $root = 'home';
	public $type = 'launcher';
	public $post_type = 'user';
	public $show_bulk_send = true;
	public $show_app_tile = true;


	public function boot() {
		add_filter( 'user_has_cap', [ $this, 'user_has_cap' ], 100, 3 );
	}


	/**
	 * Make sure the user can do everything we need them to do during this request.
	 *
	 * @param array $allcaps Existing capabilities for the user
	 * @param string $caps Capabilities provided by map_meta_cap()
	 * @param array $args Arguments for current_user_can()
	 *
	 * @return array
	 * @see WP_User::has_cap() in wp-includes/capabilities.php
	 */
	public function user_has_cap( $allcaps, $caps, $args ) {
		$allcaps['view_any_contacts'] = true;
		$allcaps['access_contacts']   = true;

		return $allcaps;
	}
}
