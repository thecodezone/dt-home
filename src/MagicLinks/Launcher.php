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


	public function boot() {
	}
}
