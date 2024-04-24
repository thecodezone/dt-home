<?php

namespace DT\Home\MagicLinks;

use DT_Magic_Url_Base;

abstract class MagicLink extends DT_Magic_Url_Base {
	private static $_instance = null;
	public $page_title = 'Magic App';
	public $page_description = 'Magic Link';
	public $root = 'magic';
	public $type = 'app';
	public $post_type = 'user';
	public $show_bulk_send = false;
	public $show_app_tile = false;
	public $meta = [];
	private $meta_key = ''; // Allows for instance specific data.

	public function __construct() {

		/**
		 * Specify metadata structure, specific to the processing of current
		 * magic link type.
		 *
		 * - meta:              Magic link plugin related data.
		 *      - app_type:     Flag indicating type to be processed by magic link plugin.
		 *      - post_type     Magic link type post type.
		 *      - contacts_only:    Boolean flag indicating how magic link type user assignments are to be handled within magic link plugin.
		 *                          If True, lookup field to be provided within plugin for contacts only searching.
		 *                          If false, Dropdown option to be provided for user, team or group selection.
		 *      - fields:       List of fields to be displayed within magic link frontend form.
		 */
		$this->meta = [
			'app_type'      => 'magic_link',
			'post_type'     => $this->post_type,
			'contacts_only' => false,
			'fields'        => [
				[
					'id'    => 'name',
					'label' => 'Name'
				]
			]
		];

		$this->meta_key = $this->root . '_' . $this->type . '_magic_key';
		parent::__construct();

		/**
		 * user_app and module section
		 */
		add_filter( 'dt_settings_apps_list', [ $this, 'dt_settings_apps_list' ], 10, 1 );
		add_action( 'rest_api_init', [ $this, 'add_endpoints' ] );

		/**
		 * tests if other URL
		 */
		$url = dt_get_url_path();
		if ( strpos( $url, $this->root . '/' . $this->type ) === false ) {
			return;
		}
		/**
		 * tests magic link parts are registered and have valid elements
		 */
		if ( ! $this->check_parts_match() ) {
			return;
		}

		$this->boot();
	} // End instance()

	abstract public function boot();

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new static();
		}

		return self::$_instance;
	}

	public function add_endpoints() {
		// Extend this function to add custom endpoints
	}
}
